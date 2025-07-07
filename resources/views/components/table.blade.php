<div class="d-flex flex-column">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    {{ $header }}
                </tr>
            </thead>
            <tbody>
                {{ $body }}
            </tbody>
        </table>
    </div>
    @if(isset($pagination))
        <div class="mt-4 d-flex justify-content-center">
            {{ $pagination }}
        </div>
    @endif
</div>