<div class="flex flex-row justify-between items-center my-2">
    <div class="flex flex-row space-x-4 items-center">
        <button class="bg-gray-100 rounded-md p-2 hover:bg-gray-200 transition" wire:click="goToPreviousMonth">
            Previous Month
        </button>
        <button class="bg-gray-100 rounded-md p-2 hover:bg-gray-200 transition" wire:click="goToNextMonth">
            Next Month
        </button>
    </div>
    <span>
        {{ $startsAt->englishMonth }} {{ $startsAt->year }}
    </span>
</div>
