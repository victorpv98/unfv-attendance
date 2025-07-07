@props(['id', 'title', 'icon' => null])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="w-100">
                    <div class="d-flex align-items-start">
                        <!-- Icon -->
                        @if($icon === 'warning')
                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle bg-danger-subtle me-3" style="width: 48px; height: 48px;">
                                <svg width="24" height="24" class="text-danger" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Title -->
                        <div class="flex-grow-1">
                            <h1 class="modal-title fs-5 fw-medium text-body-emphasis mb-0" id="{{ $id }}Label">
                                {{ $title }}
                            </h1>
                        </div>
                        
                        <!-- Close button -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                </div>
            </div>
            
            <div class="modal-body pt-2">
                <div class="text-body-secondary">
                    {{ $slot }}
                </div>
            </div>
            
            <!-- Actions -->
            <div class="modal-footer bg-body-tertiary border-0">
                <div class="d-flex flex-column flex-sm-row-reverse gap-2 w-100">
                    {{ $actions }}
                </div>
            </div>
        </div>
    </div>
</div>