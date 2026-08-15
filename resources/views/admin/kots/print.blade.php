<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <title>
        {{ $kot->kot_number }}
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin: 0 0 10px;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px 0;
            vertical-align: top;
        }

        th {
            border-bottom: 1px solid #000;
        }

        .qty {
            width: 50px;
            text-align: right;
        }

        .note {
            font-size: 12px;
        }

        @media print {

            body {
                width: 80mm;
            }

            .no-print {
                display: none;
            }

        }

    </style>

</head>


<body>


<h2>RESTAURANT</h2>


<div class="center">

    <strong>
        {{ $kot->kot_number }}
    </strong>

    <br>

    {{ $kot->created_at->format(
        'd M Y h:i A'
    ) }}

</div>


<div class="line"></div>


<div>

    <strong>
        Order:
    </strong>

    {{ $kot->order->order_number }}

</div>


<div>

    <strong>
        Table:
    </strong>

    {{ $kot->table?->table_number ?? '-' }}

</div>


<div>

    <strong>
        Customer:
    </strong>

    {{ $kot->order->customer?->name
        ?? 'Walk-in' }}

</div>


<div class="line"></div>


<table>

    <thead>

        <tr>

            <th>
                Item
            </th>

            <th class="qty">
                Qty
            </th>

        </tr>

    </thead>


    <tbody>

        @foreach(
            $kot->items
            as $item
        )

            <tr>

                <td>

                    <strong>
                        {{ $item->item_name }}
                    </strong>

                    @if($item->size)

                        <div class="note">

                            {{ $item->size }}

                        </div>

                    @endif


                    @if($item->notes)

                        <div class="note">

                            Note:
                            {{ $item->notes }}

                        </div>

                    @endif

                </td>


                <td class="qty">

                    <strong>

                        {{ $item->quantity }}

                    </strong>

                </td>

            </tr>

        @endforeach

    </tbody>

</table>


@if($kot->notes)

    <div class="line"></div>

    <strong>
        Kitchen Notes:
    </strong>

    <div>

        {{ $kot->notes }}

    </div>

@endif


<div class="line"></div>


<div class="center">

    *** KITCHEN COPY ***

</div>


<script>

window.onload = function () {

    window.print();

};

</script>


</body>

</html>