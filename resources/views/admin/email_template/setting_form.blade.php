<div id="email_template">
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 box-shadow">
                <form method="POST" action="{{ URL::to('admin/emailmessagesettings') }}">
                    @csrf
                    <div class="card-header rounded-top p-3 bg-secondary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="text-capitalize fw-600 settings-color col-6">
                                {{ trans('labels.email_message_settings') }}
                            </h5>
                            <select name="template_type" id="template_type" class="form-select">
                                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                    <option value="1" data-attribute="forgotpassword">
                                        {{ trans('labels.forgot_password') }}
                                    </option>
                                    <option value="2" data-attribute="delete_account">
                                        {{ trans('labels.delete_profile') }}
                                    </option>
                                    <option value="3" data-attribute="banktransfer_request">
                                        {{ trans('labels.banktransfer_request') }}</option>
                                    <option value="4" data-attribute="cod_request">
                                        {{ trans('labels.cod_request') }}
                                    </option>
                                    <option value="5" data-attribute="subscription_reject">
                                        {{ trans('labels.subscription_reject') }}</option>
                                    <option value="6" data-attribute="subscription_success">
                                        {{ trans('labels.subscription_success') }}</option>
                                    <option value="7" data-attribute="admin_subscription_request">
                                        {{ trans('labels.admin_subscription_request') }}</option>
                                    <option value="8" data-attribute="admin_subscription_success">
                                        {{ trans('labels.admin_subscription_success') }}</option>

                                    <option value="9" data-attribute="vendor_register">
                                        {{ trans('labels.vendor_register') }}</option>
                                    <option value="10" data-attribute="admin_vendor_register">
                                        {{ trans('labels.admin_vendor_register') }}</option>
                                    <option value="11" data-attribute="vendor_status_change">
                                        {{ trans('labels.vendor_status_change') }}</option>
                                @endif
                                <option value="12" data-attribute="contact_email">
                                    {{ trans('labels.contact_email') }}
                                </option>
                                @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                    <option value="13" data-attribute="new_order_invoice">
                                        {{ trans('labels.new_order_invoice') }}</option>
                                    <option value="14" data-attribute="vendor_new_order">
                                        {{ trans('labels.vendor_new_order') }}</option>
                                    <option value="15" data-attribute="order_status">
                                        {{ trans('labels.order_status') }}
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div id="templatemenuContent">
                                @if (Auth::user()->type == 1 || (Auth::user()->type == 4 && Auth::user()->vendor_id == 1))
                                    <div id="forgotpassword" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.forgot_password') }} {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.username') }} :
                                                <code>{user}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.password') }} :
                                                <code>{password}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="forget_password_email_message" cols="50" rows="10">{{ @$settingdata->forget_password_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="delete_account" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.delete_profile') }} {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                                <code>{vendorname}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="delete_account_email_message" cols="50" rows="10">{{ @$settingdata->delete_account_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="banktransfer_request" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.banktransfer_request') }}
                                                {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                                <code>{vendorname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.adminname') }} :
                                                <code>{adminname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.adminemail') }} :
                                                <code>{adminemail}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="banktransfer_request_email_message" cols="50" rows="10">{{ @$settingdata->banktransfer_request_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="cod_request" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.cod_request') }} {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                                <code>{vendorname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.adminname') }} :
                                                <code>{adminname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.adminemail') }} :
                                                <code>{adminemail}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="cod_request_email_message" cols="50" rows="10">{{ @$settingdata->cod_request_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="subscription_reject" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.subscription_reject') }}
                                                {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                                <code>{vendorname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.payment_type') }} :
                                                <code>{payment_type}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.planname') }} :
                                                <code>{plan_name}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.adminname') }} :
                                                <code>{adminname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.adminemail') }} :
                                                <code>{adminemail}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="subscription_reject_email_message" cols="50" rows="10">{{ @$settingdata->subscription_reject_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="subscription_success" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.subscription_success') }}
                                                {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                                <code>{vendorname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.payment_type') }} :
                                                <code>{payment_type}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.subscription_duration') }} :
                                                <code>{subscription_duration}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.subscription_cost') }} :
                                                <code>{subscription_price}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.planname') }} :
                                                <code>{plan_name}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.adminname') }} :
                                                <code>{adminname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.adminemail') }} :
                                                <code>{adminemail}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="subscription_success_email_message" cols="50" rows="10">{{ @$settingdata->subscription_success_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="admin_subscription_request" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.admin_subscription_request') }}
                                                {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.adminname') }} :
                                                <code>{adminname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                                <code>{vendorname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendoremail') }} :
                                                <code>{vendoremail}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.planname') }} :
                                                <code>{plan_name}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.subscription_duration') }} :
                                                <code>{subscription_duration}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.subscription_cost') }} :
                                                <code>{subscription_price}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.payment_type') }} :
                                                <code>{payment_type}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="admin_subscription_request_email_message" cols="50" rows="10">{{ @$settingdata->admin_subscription_request_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="admin_subscription_success" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.admin_subscription_success') }}
                                                {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.adminname') }} :
                                                <code>{adminname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                                <code>{vendorname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendoremail') }} :
                                                <code>{vendoremail}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.planname') }} :
                                                <code>{plan_name}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.subscription_duration') }} :
                                                <code>{subscription_duration}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.subscription_cost') }} :
                                                <code>{subscription_price}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.payment_type') }} :
                                                <code>{payment_type}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="admin_subscription_success_email_message" cols="50" rows="10">{{ @$settingdata->admin_subscription_success_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="vendor_register" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.vendor_register') }} {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                                <code>{vendorname}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="vendor_register_email_message" cols="50" rows="10">{{ @$settingdata->vendor_register_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="admin_vendor_register" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.admin_vendor_register') }}
                                                {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.adminname') }} :
                                                <code>{adminname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                                <code>{vendorname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendoremail') }} :
                                                <code>{vendoremail}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendormobile') }} :
                                                <code>{vendormobile}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="admin_vendor_register_email_message" cols="50" rows="10">{{ @$settingdata->admin_vendor_register_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="vendor_status_change" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.vendor_status_change') }}
                                                {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                                <code>{vendorname}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="vendor_status_change_email_message" cols="50" rows="10">{{ @$settingdata->vendor_status_change_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div id="contact_email" class="hidechild">
                                    <div class="col-12">
                                        <h5 class="text-start color-changer">
                                            {{ trans('labels.contact_email') }} {{ trans('labels.variable') }}
                                        </h5>
                                        <div class="border-bottom my-3"></div>
                                        <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                            <code>{vendorname}</code>
                                        </p>
                                        <p class="mb-1 fs-15 color-changer">{{ trans('labels.username') }} :
                                            <code>{username}</code>
                                        </p>
                                        <p class="mb-1 fs-15 color-changer">{{ trans('labels.useremail') }} :
                                            <code>{useremail}</code>
                                        </p>
                                        <p class="mb-1 fs-15 color-changer">{{ trans('labels.usermobile') }} :
                                            <code>{usermobile}</code>
                                        </p>
                                        <p class="mb-1 fs-15 color-changer">{{ trans('labels.usermessage') }} :
                                            <code>{usermessage}</code>
                                        </p>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                <span class="text-danger"> * </span> </label>
                                            <textarea class="form-control" name="contact_email_message" cols="50" rows="10">{{ @$settingdata->contact_email_message }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @if (Auth::user()->type == 2 || (Auth::user()->type == 4 && Auth::user()->vendor_id != 1))
                                    <div id="new_order_invoice" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.new_order_invoice') }}
                                                {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.customer_name') }} :
                                                <code>{customername}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.order_number') }} :
                                                <code>{ordernumber}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.date') }} :
                                                <code>{date}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.time') }} :
                                                <code>{time}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.grand_total') }} :
                                                <code>{grandtotal}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.track_order_url') }} :
                                                <code>{track_order_url}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                                <code>{vendorname}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="new_order_invoice_email_message" cols="50" rows="10">{{ @$settingdata->new_order_invoice_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="vendor_new_order" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.vendor_new_order') }}
                                                {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                                <code>{vendorname}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.order_number') }} :
                                                <code>{ordernumber}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.date') }} :
                                                <code>{date}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.time') }} :
                                                <code>{time}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.grand_total') }} :
                                                <code>{grandtotal}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.customer_name') }} :
                                                <code>{customername}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="vendor_new_order_email_message" cols="50" rows="10">{{ @$settingdata->vendor_new_order_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="order_status" class="hidechild">
                                        <div class="col-12">
                                            <h5 class="text-start color-changer">
                                                {{ trans('labels.order_status') }} {{ trans('labels.variable') }}
                                            </h5>
                                            <div class="border-bottom my-3"></div>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.customer_name') }} :
                                                <code>{customername}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.status_message') }} :
                                                <code>{status_message}</code>
                                            </p>
                                            <p class="mb-1 fs-15 color-changer">{{ trans('labels.vendorname') }} :
                                                <code>{vendorname}</code>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label fw-bold">{{ trans('labels.email_message') }}
                                                    <span class="text-danger"> * </span> </label>
                                                <textarea class="form-control" name="order_status_email_message" cols="50" rows="10">{{ @$settingdata->order_status_email_message }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div
                                class="mt-3 {{ session()->get('direction') == '2' ? 'text-start' : 'text-end' }}">
                                <button
                                    class="btn btn-primary px-sm-4 {{ Auth::user()->type == 4 ? (helper::check_access('role_settings', Auth::user()->role_id, Auth::user()->vendor_id, 'edit') == 1 ? '' : 'd-none') : '' }}"
                                    @if (env('Environment') == 'sendbox') type="button" onclick="myFunction()" @else type="submit" @endif>{{ trans('labels.save') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
