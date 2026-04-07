<!DOCTYPE html>
<html>

<head>
    <title>{{ helper::appdata('')->web_title }}</title>
    <style type="text/css">
        body {
            font-family: 'Roboto Condensed', sans-serif;
            margin: 0;
            padding: 0;
        }

        .text-center {
            text-align: center !important;
        }

        .w-100 {
            width: 100%;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #d2d2d2;
        }

        th {
            background-color: #F4F4F4;
            font-size: 15px;
            padding: 7px 8px;
        }

        td {
            font-size: 13px;
            padding: 7px 8px;
        }

        .header-title {
            margin-bottom: 20px;
        }

        .group-header {
            font-size: 18px;
            margin-top: 20px;
        }

        .city-list {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header-title">
        <h1 class="text-center">{{ trans('labels.city_list') }}</h1>
    </div>

    <div class="table-section mt-10">
        <table class="table">
            <tbody>
                @php
                    $groupedCities = $citieslist->groupBy('country_id');
                @endphp

                @foreach($groupedCities as $countryId => $citiesByCountry)
                    <tr>
                        <th class="group-header">
                           
                            {{ $citiesByCountry->first()->country_name }}
                        </th>
                        <th>
                            {{ $citiesByCountry->first()->country_id }}
                            
                        </th>
                    </tr>
                    @foreach($citiesByCountry as $city)
                        <tr>
                            <td>
                                {{ $city->city }}
                            </td>
                            <td>
                                {{ $city->id }}
                                
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
