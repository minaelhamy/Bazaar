@if (helper::top_deals(@$storeinfo->id) != '' && count(helper::topdalsitemlist(@$storeinfo->id)) > 0)
    <nav class="background-black bg-change-modes p-3">
        <div class="container">
            <div id="eapps-countdown-timer-1"
                class="rounded eapps-countdown-timer eapps-countdown-timer-align-center eapps-countdown-timer-position-bottom-bar-floating eapps-countdown-timer-animation-none eapps-countdown-timer-theme-default eapps-countdown-timer-finish-button-show   eapps-countdown-timer-style-combined eapps-countdown-timer-style-blocks eapps-countdown-timer-position-bar eapps-countdown-timer-area-clickable eapps-countdown-timer-has-background">
                <div class="eapps-countdown-timer-container d-flex">
                    <div class="eapps-countdown-timer-inner row g-sm-0 g-3 flex-column flex-sm-row">
                        <div
                            class="eapps-countdown-timer-header d-flex col-md-4 align-items-center justify-content-center justify-content-md-start">
                            <div class="eapps-countdown-timer-header-title ">
                                <div class="eapps-countdown-timer-header-title-text text-center {{ session()->get('direction') == 2 ? 'text-md-end' : 'text-md-start' }}">
                                    <div class="line-2">{{ helper::top_deals(@$storeinfo->id)->description }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="eapps-countdown-timer-item-container col-md-4">
                            <div class="eapps-countdown-timer-item d-flex gap-2 justify-content-center countdowntime">
                            </div>
                        </div>
                        <div
                            class="eapps-countdown-timer-button-container d-flex col-md-4 align-items-center justify-content-center justify-content-md-end">
                            <a href="{{ helper::storefront_url($storeinfo, '/search?type=topdeals') }}"
                                class="eapps-countdown-timer-button rounded">
                                {{ trans('labels.shop_now') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
@endif
