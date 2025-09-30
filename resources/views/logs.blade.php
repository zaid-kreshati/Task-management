@extends('layouts.BeeOrder_header')

@section('content')
    <table>
        <thead>
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Model') }}</th>
                <th>{{ __('Model ID') }}</th>
                <th>{{ __('Action') }}</th>
                <th>{{ __('Old Model') }}</th>
                <th>{{ __('New Model') }}</th>
                <th>{{ __('Deleted Model') }}</th>
                <th>{{ __('Action By') }}</th>
                <th>{{ __('Created Time') }}</th>
                <th>{{ __('Updated Time') }}</th>
                <th>{{ __('Deleted Time') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td style="text-align: left;">{{ $log->model }}</td>
                    <td>{{ $log->model_id }}</td>
                    <td>{{ $log->action }}</td>
                    <td style="text-align: left;">{!! displayModelData(safeJsonDecode($log->old_model)) !!}</td>
                    <td style="text-align: left;">{!! displayModelData(safeJsonDecode($log->new_model)) !!}</td>
                    <td style="text-align: left;">{!! displayModelData(safeJsonDecode($log->deleted_model)) !!}</td>
                    <td>{{ $log->action_by }}</td>
                    <td>{{ $log->createdTime }}</td>
                    <td>{{ $log->updatedTime }}</td>
                    <td>{{ $log->deletedTime }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @php
        /**
         * Safely decode JSON and handle any errors.
         *
         * @param string|null $json
         * @return array
         */
        function safeJsonDecode($json)
        {
            if (empty($json)) {
                return [];
            }

            $data = json_decode($json, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return []; // Fallback to empty array if JSON is malformed
            }

            return $data;
        }

        /**
         * Recursively display model data as a list, handling nested arrays.
         *
         * @param array $data
         * @return string
         */
        function displayModelData($data)
        {
            if (empty($data)) {
                return '<i>' . __('No data') . '</i>';
            }

            $output = '<ul>';
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $output .= '<li>' . ucfirst(__($key)) . ': ' . displayModelData($value) . '</li>';
                } else {
                    if ($key === 'color') {
                        $output .=
                            '<li>' .
                            ucfirst(__($key)) .
                            ': <span class="color-box" style="background-color: ' .
                            htmlspecialchars($value) .
                            ';"></span></li>';
                    } else {
                        $output .= '<li>' . ucfirst(__($key)) . ': ' . htmlspecialchars($value) . '</li>';
                    }
                }
            }
            $output .= '</ul>';
            return $output;
        }
    @endphp
@endsection
