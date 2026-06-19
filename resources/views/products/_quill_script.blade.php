<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['blockquote', 'link'],
                ['clean']
            ]
        },
        placeholder: 'Enter product description…'
    });

    @isset($existingContent)
    quill.root.innerHTML = {!! json_encode($existingContent) !!};
    @endisset

    const form = quill.container.closest('form');
    form.addEventListener('submit', function () {
        document.getElementById('description-input').value = quill.root.innerHTML;
    });
});
</script>
