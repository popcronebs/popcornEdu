<div class="spinner-wrap {{ $attributes->get('class') }}" style="{{ $attributes->get('style') }}">
    <div class="spinner d-flex flex-column justify-content-center align-items-center gap-3" role="status">
        <span class="spinner-item"  style="{{ $attributes->get('item-style') }}">
            <img src="/images/svg/spinner.svg" alt="로딩중">
        </span>
        <span class="spinner-text">
            <img src="/images/svg/spinner_text.svg" alt="로딩중">
        </span>
    </div>
</div>
