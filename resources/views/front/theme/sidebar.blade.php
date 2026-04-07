<div class=" col-xl-3 col-lg-4 col-xxl-3 mb-3 mb-lg-0">
    <div class="card-v bg-light card border-0 p-3 rounded d-none d-lg-block">
        <div class="title-and-image border-bottom pb-3">
            <div class="user-image">
                <img src="{{ helper::image_path(@Auth::user()->image) }}" alt=""
                    class="object-fit-cover img-fluid">
            </div>
            <div class="mx-3">
                <h5 class="title color-changer my-0 line-1">{{ @Auth::user()->name }}</h5>
                <p class="fs-7 text-muted">{{ @Auth::user()->email }}</p>
            </div>
        </div>
        <div class="user-list-saide-bar mt-4">
            <ul class="p-0 m-0">
                <a href="{{ helper::storefront_url($storeinfo, '/profile') }}" class="settings-link color-changer text-dark">
                    <li
                        class="list-unstyled d-flex align-items-center justify-content-between border-0 my-2 p-3 rounded {{ helper::storefront_request_is($storeinfo, 'profile*') ? 'active-menu' : '' }}">
                        <div class="d-flex align-items-center">
                            <i class="fa-light fa-user"></i><span class="px-3">{{ trans('labels.profile') }}</span>
                        </div>
                        <i class="fa-solid fa-angle-right float-end"></i>
                    </li>
                </a>
                @php
                    $orders = helper::getorders($storeinfo->id, Auth::user()->id);
                @endphp
                @if (helper::appdata($storeinfo->id)->online_order == 1 || $orders->count() > 0)
                    <a href="{{ helper::storefront_url($storeinfo, '/orders') }}" class="settings-link color-changer text-dark">
                        <li
                            class="list-unstyled d-flex align-items-center justify-content-between border-0 my-2 p-3 rounded {{ helper::storefront_request_is($storeinfo, 'orders*') ? 'active-menu' : '' }}">
                            <div class="d-flex align-items-center">
                                <i class="fa-light fa-list-check"></i><span
                                    class="px-3">{{ trans('labels.orders') }}</span>
                            </div>
                            <i class="fa-solid fa-angle-right float-end"></i>
                        </li>
                    </a>
                @endif
                <a href="{{ helper::storefront_url($storeinfo, '/wishlist') }}" class="settings-link color-changer text-dark ">
                    <li
                        class="list-unstyled d-flex align-items-center justify-content-between border-0 my-2 p-3 rounded {{ helper::storefront_request_is($storeinfo, 'wishlist') ? 'active-menu' : '' }}">
                        <div class="d-flex align-items-center">
                            <i class="fa-light fa-heart"></i><span class="px-3">{{ trans('labels.wishlist') }}</span>
                        </div>
                        <i class="fa-solid fa-angle-right float-end"></i>
                    </li>
                </a>
                @if (helper::allpaymentcheckaddons($storeinfo->id))
                    <a href="{{ helper::storefront_url($storeinfo, '/wallet') }}" class="settings-link color-changer text-dark ">
                        <li
                            class="list-unstyled d-flex align-items-center justify-content-between border-0 my-2 p-3 rounded {{ helper::storefront_request_is($storeinfo, 'wallet*') ? 'active-menu' : '' }}">
                            <div class="d-flex align-items-center">
                                <i class="fa-light fa-wallet"></i>
                                <span class="px-3">{{ trans('labels.wallet') }}</span>
                            </div>
                            <i class="fa-solid fa-angle-right float-end"></i>
                        </li>
                    </a>
                @endif
                @if (@Auth::user()->google_id == '' && @Auth::user()->facebook_id == '')
                    <a href="{{ helper::storefront_url($storeinfo, '/change-password') }}" class="settings-link color-changer text-dark">
                        <li
                            class="list-unstyled d-flex align-items-center justify-content-between border-0 my-2 p-3 rounded {{ helper::storefront_request_is($storeinfo, 'change-password*') ? 'active-menu' : '' }}">
                            <div class="d-flex align-items-center">
                                <i class="fa-light fa-key"></i><span
                                    class="px-3">{{ trans('labels.change_password') }}</span>
                            </div>
                            <i class="fa-solid fa-angle-right float-end"></i>
                        </li>
                    </a>
                @endif
                <a href="{{ helper::storefront_url($storeinfo, '/delete-password') }}" class="settings-link color-changer text-dark ">
                    <li
                        class="list-unstyled d-flex align-items-center justify-content-between border-0 my-2 p-3 rounded {{ helper::storefront_request_is($storeinfo, 'delete-password*') ? 'active-menu' : '' }}">
                        <div class="d-flex align-items-center">
                            <i class="fa-light fa-trash"></i><span
                                class="px-3">{{ trans('labels.delete_profile') }}</span>
                        </div>
                        <i class="fa-solid fa-angle-right float-end"></i>
                    </li>
                </a>
                <a href="javascript:void(0)" onclick="deletedata('{{ helper::storefront_url($storeinfo, '/logout') }}')"
                    class="settings-link color-changer text-dark ">
                    <li
                        class="list-unstyled d-flex align-items-center justify-content-between border-0 my-2 p-3 rounded">
                        <div class="d-flex align-items-center">
                            <i class="fa-light fa-arrow-right-from-bracket"></i><span
                                class="px-3">{{ trans('labels.logout') }}</span>
                        </div>
                        <i class="fa-solid fa-angle-right float-end"></i>
                    </li>
                </a>
            </ul>
        </div>
    </div>
</div>

<div class="accordion d-lg-none mb-3" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button
                class="accordion-button collapsed fw-500 bg-light d-flex gap-2 justify-content-between align-items-center text-dark m-0"
                type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                aria-controls="collapseTwo">
                <div class=" d-flex gap-2">
                    <i class="fa-light fa-bars-staggered"></i>
                    {{ trans('labels.dashboard_navigation') }}
                </div>
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <ul class="m-0 p-0">
                    <li class="list-unstyled border-0 rounded">
                        <a href="{{ helper::storefront_url($storeinfo, '/profile') }}"
                            class="border d-block mb-3 p-3 rounded-2 {{ helper::storefront_request_is($storeinfo, 'profile') ? 'active' : '' }}">
                            <i class="fa-light fa-user"></i>
                            <span class="px-2">{{ trans('labels.profile') }}</span>
                        </a>
                    </li>
                    <li class="list-unstyled border-0 rounded">
                        <a href="{{ helper::storefront_url($storeinfo, '/orders') }}"
                            class="border d-block mb-3 p-3 rounded-2 {{ helper::storefront_request_is($storeinfo, 'orders') ? 'active' : '' }}">
                            <i class="fa-light fa-list-check"></i>
                            <span class="px-2">{{ trans('labels.orders') }}</span>
                        </a>
                    </li>
                    <li class="list-unstyled border-0 rounded">
                        <a href="{{ helper::storefront_url($storeinfo, '/wishlist') }}"
                            class="border d-block mb-3 p-3 rounded-2 {{ helper::storefront_request_is($storeinfo, 'wishlist') ? 'active' : '' }}">
                            <i class="fa-light fa-heart"></i>
                            <span class="px-2">{{ trans('labels.wishlist') }}</span>
                        </a>
                    </li>
                    @if (helper::allpaymentcheckaddons($storeinfo->id))
                        <li class="list-unstyled border-0 rounded">
                            <a href="{{ helper::storefront_url($storeinfo, '/wallet') }}"
                                class="border d-block mb-3 p-3 rounded-2 {{ helper::storefront_request_is($storeinfo, 'wallet*') ? 'active' : '' }}">
                                <i class="fa-light fa-wallet"></i>
                                <span class="px-2">{{ trans('labels.wallet') }}</span>
                            </a>
                        </li>
                    @endif
                    <li class="list-unstyled border-0 rounded">
                        <a href="{{ helper::storefront_url($storeinfo, '/change-password') }}"
                            class="border d-block mb-3 p-3 rounded-2 {{ helper::storefront_request_is($storeinfo, 'change-password') ? 'active' : '' }}">
                            <i class="fa-light fa-key"></i>
                            <span class="px-2">{{ trans('labels.change_password') }}</span>
                        </a>
                    </li>
                    <li class="list-unstyled border-0 rounded">
                        <a href="{{ helper::storefront_url($storeinfo, '/delete-password') }}"
                            class="border d-block mb-3 p-3 rounded-2 {{ helper::storefront_request_is($storeinfo, 'delete-password*') ? 'active' : '' }}">
                            <i class="fa-light fa-trash"></i>
                            <span class="px-2">{{ trans('labels.delete_profile') }}</span>
                        </a>
                    </li>
                    <li class="list-unstyled border-0 rounded">
                        <a href="javascript:void(0)"
                            onclick="deletedata('{{ helper::storefront_url($storeinfo, '/logout') }}')"
                            class="border d-block mb-3 p-3 rounded-2">
                            <i class="fa-light fa-arrow-right-from-bracket"></i>
                            <span class="px-2">{{ trans('labels.logout') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
