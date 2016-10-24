<html>
    <body>
        <table>
            <tbody>
                <tr>
                    <td>Order #{{ $order->id }}</td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{$item->code}}</td>
                        <td>{{$item->description}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->price}}</td>
                        <td>{{($item->quantity * $item->price)}}</td>
                    </tr>
                @endforeach
            </tbody>
            <table>
                <tbody>
                    <tr>
                        <td align="right">Total</td>
                        <td align="right">{{ $order->getTotal() }}</td>
                    </tr>
                </tbody>
            </table>
        </table>
    </body>
</html>