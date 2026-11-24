<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Exams\Exam;
use App\Models\Training\Exams\ExamAttempt;
use App\Models\Training\Exams\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Question bank, settings and attempt records for the entrance exam. */
class ExamAdminController extends Controller
{
    private function exam(): Exam
    {
        return Exam::where('slug', 'gander-oca-entrance-exam')->firstOrFail();
    }

    public function index()
    {
        $exam = $this->exam();
        $questions = $exam->questions()->with('options')->orderBy('id')->get();
        $attempts = ExamAttempt::where('exam_id', $exam->id)->with('user')->latest('started_at')->limit(200)->get();

        return view('admin.training.exams.index', compact('exam', 'questions', 'attempts'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'pass_percentage' => 'required|integer|min:1|max:100',
            'questions_to_ask' => 'required|integer|min:0|max:500',
            'initial_retake_days' => 'required|integer|min:0|max:365',
            'subsequent_retake_days' => 'required|integer|min:0|max:365',
            'time_limit_minutes' => 'nullable|integer|min:1|max:1440',
            'notify_cids' => ['nullable', 'string', 'max:255', 'regex:/^[0-9,\s]*$/'],
        ]);
        // Keep only the CIDs, comma separated
        $data['notify_cids'] = implode(',', array_unique(preg_split('/[^0-9]+/', (string) ($data['notify_cids'] ?? ''), -1, PREG_SPLIT_NO_EMPTY))) ?: null;
        $exam = $this->exam();
        $exam->update($data + ['active' => $request->boolean('active')]);

        return back()->with('success', 'Exam settings saved.');
    }

    public function storeQuestion(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string|max:2000',
            'options' => 'required|array|min:2|max:8',
            'options.*' => 'nullable|string|max:1000',
            'correct' => 'required|integer|min:0|max:7',
        ]);
        $options = collect($data['options'])->map(fn ($o) => trim((string) $o))->filter(fn ($o) => $o !== '');
        $position = array_search($data['correct'], $options->keys()->all(), true);
        if ($options->count() < 2 || $position === false) {
            return back()->withInput()->with('error', 'Enter at least two options and tick one that exists as the correct answer.');
        }
        $this->createQuestion($this->exam(), $data['question'], $options->values()->all(), $position, $request->boolean('required'));

        return back()->with('success', 'Question added.');
    }

    public function toggleQuestion(ExamQuestion $question)
    {
        $question->update(['active' => ! $question->active]);

        return back()->with('success', $question->active ? 'Question enabled.' : 'Question disabled.');
    }

    public function deleteQuestion(ExamQuestion $question)
    {
        if ($question->exam_id !== $this->exam()->id) {
            abort(404);
        }
        // Past attempts keep pointing at it, so disable instead when it has been used.
        if (DB::table('exam_attempt_answers')->where('exam_question_id', $question->id)->exists()) {
            $question->update(['active' => false]);

            return back()->with('info', 'This question has been asked before, so it was disabled instead of deleted.');
        }
        $question->delete();

        return back()->with('success', 'Question deleted.');
    }

    /**
     * CSV columns: question, option 1..N (up to 8), correct, required.
     * "correct" is the option letter (A, B, C…) or number (1, 2, 3…). "required" is optional (yes/1/true).
     */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|max:2048']);
        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($handle);
        if (! $header) {
            return back()->with('error', 'That file is empty.');
        }
        $header = array_map(fn ($h) => strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', (string) $h))), $header);
        $exam = $this->exam();
        $added = 0;
        $skipped = [];
        $line = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $line++;
            if (count(array_filter($row, fn ($c) => trim((string) $c) !== '')) === 0) {
                continue;
            }
            $r = array_combine($header, array_pad(array_slice($row, 0, count($header)), count($header), ''));
            $options = [];
            foreach ($header as $col) {
                if (preg_match('/^option[ _]?\d+$/', $col) && trim($r[$col]) !== '') {
                    $options[] = trim($r[$col]);
                }
            }
            $correct = $this->parseCorrect($r['correct'] ?? '');
            if (trim($r['question'] ?? '') === '' || count($options) < 2 || $correct === null || ! isset($options[$correct])) {
                $skipped[] = $line;

                continue;
            }
            $this->createQuestion($exam, trim($r['question']), $options, $correct, in_array(strtolower(trim($r['required'] ?? '')), ['1', 'yes', 'true', 'y'], true));
            $added++;
        }
        fclose($handle);

        $msg = "Imported $added question(s).";
        if ($skipped) {
            $msg .= ' Skipped line(s) with a missing question, fewer than two options or no valid correct answer: '.implode(', ', $skipped).'.';
        }

        return back()->with($skipped ? 'info' : 'success', $msg);
    }

    private function parseCorrect($value): ?int
    {
        $value = strtoupper(trim((string) $value));
        if ($value === '') {
            return null;
        }
        if (ctype_digit($value)) {
            return (int) $value - 1;
        }
        if (strlen($value) === 1 && $value >= 'A' && $value <= 'H') {
            return ord($value) - 65;
        }

        return null;
    }

    private function createQuestion(Exam $exam, string $text, array $options, int $correctIndex, bool $required): ExamQuestion
    {
        return DB::transaction(function () use ($exam, $text, $options, $correctIndex, $required) {
            $q = $exam->questions()->create(['question' => $text, 'required' => $required, 'active' => true]);
            foreach (array_values($options) as $i => $o) {
                $q->options()->create(['option_text' => $o, 'is_correct' => $i === $correctIndex]);
            }

            return $q;
        });
    }
}
