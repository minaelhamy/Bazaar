<?php

namespace App\Imports;

use App\Models\CustomStatus;
use App\Models\LandingSettings;
use App\Models\Payment;
use App\Models\Settings;
use App\Models\Timing;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportVendor implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        try {
            foreach ($rows as $row) {
                $rec = Settings::where('vendor_id', '1')->first();
                if (!empty($row)) {
                    $user = new User();
                    $user->id = $row['id'];
                    $user->store_id = $row['store_id'];
                    $user->name = $row['name'];
                    $user->slug = $row['slug'];
                    $user->email = $row['email'];
                    $user->mobile = $row['mobile'];
                    $user->password = Hash::make($row['password']);
                    $user->google_id = '';
                    $user->facebook_id = '';
                    $user->image = "default.png";
                    $user->login_type = '';
                    $user->type = 2;
                    $user->token = '';
                    $user->country_id = $row['country_id'];
                    $user->city_id = $row['city_id'];
                    $user->vendor_id = $row['vendor_id'];
                    $user->is_verified = 2;
                    $user->is_available = 1;
                    $user->store_id = '';
                    $user->save();

                    $vendor_id = \DB::getPdo()->lastInsertId();
                    $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

                    foreach ($days as $day) {

                        $timedata = new Timing;
                        $timedata->vendor_id = $vendor_id;
                        $timedata->day = $day;
                        $timedata->open_time = '09:00 AM';
                        $timedata->break_start = '01:00 PM';
                        $timedata->break_end = '02:00 PM';
                        $timedata->close_time = '09:00 PM';
                        $timedata->is_always_close = '2';
                        $timedata->save();
                    }
                    $status_name = CustomStatus::where('vendor_id', '1')->get();

                    foreach ($status_name as $name) {
                        $customstatus = new CustomStatus;
                        $customstatus->vendor_id = $vendor_id;
                        $customstatus->name = $name->name;
                        $customstatus->type = $name->type;
                        $customstatus->order_type = $name->order_type;
                        $customstatus->is_available = $name->is_available;
                        $customstatus->is_deleted = $name->is_deleted;
                        $customstatus->save();
                    }

                    $paymentlist = Payment::select('payment_name', 'currency', 'image', 'is_activate', 'payment_type')->where('vendor_id', '1')->get();
                    foreach ($paymentlist as $payment) {
                        $gateway = new Payment;
                        $gateway->vendor_id = $vendor_id;
                        $gateway->payment_name = $payment->payment_name;
                        $gateway->currency = $payment->currency;
                        $gateway->image = $payment->image;
                        $gateway->payment_type = $payment->payment_type;
                        $gateway->public_key = '-';
                        $gateway->secret_key = '-';
                        $gateway->encryption_key = '-';
                        $gateway->environment = '1';
                        $gateway->payment_description = '-';
                        $gateway->is_available = '1';
                        $gateway->is_activate = $payment->is_activate;
                        $gateway->save();
                    }

                    $messagenotification = "Hi, 
        I would like to place an order 👇
        *{delivery_type}* Order No: {order_no}
        ---------------------------
        {item_variable}
        ---------------------------
        👉Subtotal : {sub_total}
        {total_tax}
        👉Delivery charge : {delivery_charge}
        👉Discount : - {discount_amount}
        ---------------------------
        📃 Total : {grand_total}
        ---------------------------
        📄 Comment : {notes}

        ✅ Customer Info

        Customer name : {customer_name}
        Customer phone : {customer_mobile}

        📍 Delivery Details

        Address : {address}, {building}, {landmark}, {postal_code}

        ---------------------------
        Date : {date}
        Time : {time}
        ---------------------------
        💳 Payment type :
        {payment_type}

        {store_name} will confirm your order upon receiving the message.

        Track your order 👇
        {track_order_url}

        Click here for next order 👇
        {store_url}";

                    $data = new Settings;
                    $landingsettings = LandingSettings::where('vendor_id', 1)->first();
                    $data->vendor_id = $vendor_id;
                    $data->currency = $rec->currency;
                    // logo===================================================
                    $data->logo = "default.png";

                    // favicon=============
                    $data->favicon = "default.png";

                    // og_image
                    $data->og_image = "default.png";

                    $data->currency_position = $rec->currency_position;
                    $data->timezone = $rec->timezone;
                    $data->contact = '-';
                    $data->description = $rec->description;
                    $data->copyright = $rec->copyright;
                    $data->website_title = $rec->website_title;
                    $data->meta_title = $rec->meta_title;
                    $data->meta_description = $rec->meta_description;
                    $data->delivery_type = 'delivery';
                    $data->item_message = "🔵 {qty} X {item_name} {variantsdata} - {item_price}";
                    $data->interval_time = 1;
                    $data->interval_type = 2;
                    $data->whatsapp_message = $messagenotification;
                    $data->telegram_message = $messagenotification;
                    $data->product_type = 1;
                    $data->decimal_separator = $rec->decimal_separator;
                    $data->currency_formate = $rec->currency_formate;
                    $data->time_format = $rec->time_format;
                    $data->date_format = $rec->date_format;
                    $data->order_prefix = 'PITS';
                    $data->order_number_start = 1001;
                    $data->firebase = '-';
                    $data->shopify_store_url = '-';
                    $data->shopify_access_token = '-';
                    $data->primary_color = $landingsettings->primary_color;
                    $data->secondary_color = $landingsettings->secondary_color;
                    $data->save();
                }
            }
        } catch (\Throwable $th) {
            dd($th);
            return $th;
        }
    }
}
