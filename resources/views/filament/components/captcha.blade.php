<div class="flex justify-center my-4" wire:ignore>
    <div class="g-recaptcha" 
         data-sitekey="{{ config('services.recaptcha.site_key') }}"
         data-callback="onCaptchaVerified"
         data-expired-callback="onCaptchaExpired"></div>
</div>

<script>
    function onCaptchaVerified(token) {
        const input = document.querySelector('[data-captcha-token]');
        if (input) {
            input.value = token;
            input.dispatchEvent(new Event('input'));
        }
    }
    
    function onCaptchaExpired() {
        const input = document.querySelector('[data-captcha-token]');
        if (input) {
            input.value = '';
            input.dispatchEvent(new Event('input'));
        }
    }

    document.addEventListener('livewire:initialized', () => {
        window.addEventListener('reset-captcha', () => {
            if (typeof grecaptcha !== 'undefined' && document.querySelector('.g-recaptcha')) {
                grecaptcha.reset();
                onCaptchaExpired();
            }
        });
    });
</script>

@once
    @push('scripts')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endpush
@endonce
