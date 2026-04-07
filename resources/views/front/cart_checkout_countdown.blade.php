@if (helper::appdata($storeinfo->id)->cart_checkout_countdown == 1)
    <div class="my-2">
        <div class="alert alert-warning fs-7 fw-500 text-center p-2" role="alert">
            <span id="countdown_message" class="text-capitalize">
                @php
                    $var = ['{fire}', '{timer}'];
                    $newvar = [
                        "<i class='fa-solid fa-fire'></i>",
                        "<span id='timer' class='fs-15 fw-600 text-primary'></span>",
                    ];
                    $countdown_message = str_replace($var, $newvar, helper::appdata($storeinfo->id)->countdown_message);
                @endphp
                {!! $countdown_message !!}
            </span>
            <span id="countdown_after_message" class="text-danger blink_me fw-bold text-capitalize" style="display: none;">
                {{ helper::appdata($storeinfo->id)->countdown_expired_message }}
            </span>
        </div>
    </div>

    <script>
        var sec = "{{ helper::appdata($storeinfo->id)->countdown_mins * 60 }}",
            countDiv = document.getElementById("timer"),
            secpass,
            countDown = setInterval(function() {
                'use strict';

                secpass();
            }, 1000);

        function secpass() {
            'use strict';

            var min = Math.floor(sec / 60),
                remSec = sec % 60;

            if (remSec < 10) {

                remSec = '0' + remSec;

            }
            if (min < 10) {

                min = '0' + min;

            }
            countDiv.innerHTML = min + ":" + remSec;

            if (sec > 0) {

                sec = sec - 1;

            } else {

                $("#countdown_message").hide();
                $("#countdown_after_message").show();

            }
        }
    </script>
@endif
