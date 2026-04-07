@if (@helper::checkaddons('google_recaptcha'))
    @if (helper::adminappdata()->recaptcha_version == 'v2')
        <div class="col-12">
            <div class="g-recaptcha" data-sitekey="{{ helper::adminappdata()->google_recaptcha_site_key }}"></div>
            @if ($errors->has('g-recaptcha-response'))
                <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
            @endif
        </div>
    @endif

    @if (helper::adminappdata()->recaptcha_version == 'v3')
        <div class="col-12">
            {!! RecaptchaV3::field('contact') !!}
            @if ($errors->has('g-recaptcha-response'))
                <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
            @endif
        </div>
    @endif
@endif
