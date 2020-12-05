@extends('admin.publications.layouts.main')
@section('publications-content')
    <a href="{{route('publications.custom-pages')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Custom Pages</a>
    <h1 class="font-weight-bold blue-text pb-2">{{$page->name}}</h1>
    <textarea>
        Welcome to TinyMCE!
      </textarea>
      <script>
        tinymce.init({
        selector: 'textarea',
        plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
        toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        });

        $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            try {
            return results[1] || 0;
            } catch {
                return 0;
            }
        }
    </script>
@endsection
