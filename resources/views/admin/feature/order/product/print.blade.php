<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print resi</title>
    <style>
    	body {
  		  font-family: Arial, sans-serif;
		}

		.header {
		    display: flex;
		    justify-content: space-between;
		    align-items: center;
		    margin-bottom: 20px;
		}

		.logo {
		    width: 150px;
		}

		.invoice {
		    font-size: 1.2em;
		}

		.logistics,
		.address,
		.from {
		    margin-bottom: 20px;
		}

		.address,
		.from {
		    border-top: 1px solid #ddd;
		    padding-top: 10px;
		}

		strong {
		    display: block;
		    margin-bottom: 5px;
		}

		.product table {
		    width: 100%;
		    border-collapse: collapse;
		}

		.product th,
		.product td {
		    border: 1px solid #ddd;
		    padding: 8px;
		    text-align: left;
		}

		.product th {
		    background-color: #f4f4f4;
		}

    </style>
</head>
<body>
	<table width="100%">
		<tr>
			<td colspan="2">
				<div class="header">
		            <div class="invoice">
		            	<p>Astraea Leather Craft</p>
		                <p>{{ $data['no_nota'] }}</p>
		            </div>
		        </div>
			</td>
		</tr>
		<tr class="logistics">
			<td>
	            <p>Custom Logistik</p>
	            <p>{{ strtoupper($data['shipping_data']['name']) }}<strong>{{ $data['shipping_data']['service'] }}</strong></p>    
			</td>
			<td>
				<p>Berat: {{ $data['shipping_data']['weight'] }} gram</p>
		        <p>Ongkir: Rp  {{  number_format($data['shipping_data']['price'], 0, ",", ".") }}</p>
			</td>
		</tr>
		<tr class="address">
			<td>
		            <p><strong>Kepada:</strong></p>
		            <p>{{ $data['customer_data']['name'] }}</p>
		            <p>{{ $data['shipping_data']['district_data']['province']}}, {{ $data['shipping_data']['district_data']['type']}} {{ $data['shipping_data']['district_data']['name']}}</p>
		            <p>{{ $data['address'] }}</p>
		            <p>{{ $data['customer_data']['phone']}}</p>
			</td>
			<td>
		            <p><strong>Dari:</strong></p>
		            <p>Astraea Leather Craft</p>
		            <p>{{ $data['contact']['districtData']['province'] }}, {{ $data['contact']['districtData']['type'] }} {{ $data['contact']['districtData']['name'] }}, {{ $data['contact']['districtData']['address'] }}</p>
		            <p>{{ $data['contact']['phone_number'] }}</p>
			</td>
		</tr>
		<tr>
			<td colspan="2">
		        <div class="product">
		            <table>
		                <thead>
		                    <tr>
		                        <th>Produk</th>
		                        <th>Variant</th>
		                        <th>Jumlah</th>
		                    </tr>
		                </thead>
		                <tbody>
		                	@foreach($data['product'] as $product)
		                    <tr>
		                        <td>{{ $product['productData']['name'] }}</td>
		                        <td>{{ $product['productData']['colorAtr']['name'] }}</td>
		                        <td>{{ $product['quantity'] }} pcs</td>
		                    </tr>
		                    @endforeach
		                </tbody>
		            </table>
		        </div>
				
			</td>
		</tr>
	</table>
</body>
</html>
