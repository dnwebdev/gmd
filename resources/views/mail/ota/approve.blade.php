<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mail Approve OTA</title>
</head>
<body>
    <table style="padding: 15px;font-family: 'Montserrat', sans-serif; width: 680px;margin: auto;">
        <tr>
            <td>
                <table style="width: 100%">
                    <tr>
                    <td><img src="{{ asset('img/gomodo.png') }}" alt="Logo gomodo" width="150"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="height: 40px"></td>
        </tr>
        <tr>
            <td style="font-size: 35px; text-align: center;font-weight: 500;">Hi {{$product->company->company_name}}</td>
        </tr>
        <tr>
            <td style="height: 20px;"></td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <img src="{{ asset('img/berhasil.png') }}" alt="Logo berhasi;" width="500">
            </td>
        </tr>
        <tr>
            <td style="height: 40px"></td>
        </tr>
        <tr>
            <td style="text-align: center;">
                    Terima kasih atas antusiasme Anda dalam mendaftarkan produk Anda ke dalam OTA. Melalui email ini, kami memberitahukan bahwa produk '{{$product->product_name}}' sudah DISETUJUI untuk ditampilkan di dalam OTA '{{$ota->ota_name}}'.
            </td>
        </tr>
        <tr>
            <td style="height: 30px;"></td>
        </tr>
        <tr>
            <td style="text-align: center">
                    Produk '{{$product->product_name}}' akan secara otomatis muncul di OTA '{{$ota->ota_name}}' dan akan semakin banyak menjangkau pelanggan!
            </td>
        </tr>
        <tr>
                <td style="height: 40px;"></td>
            </tr>
        <tr>
            <td style="height: 40px; border-top: 3px solid black;"></td>
        </tr>
        <tr>
            <td style="text-align: center;">
                Thank you for your enthusiasm in applying your product for our OTAs. Through this email, we would like to inform you that your product '{{$product->product_name}}' has been APPROVED to be shown in OTA '{{$ota->ota_name}}'
            </td>
        </tr>
        <tr>
            <td style="height: 30px;"></td>
        </tr>
        <tr>
            <td style="text-align: center">
                '{{$product->product_name}}' will automatically be available in OTA '{{$ota->ota_name}}' and will surely help this product reach more customers!
            </td>
        </tr>
        <tr>
            <td style="height: 20px;"></td>
        </tr>
        <tr>
            <td style="background-color: #175d92;">
                <table style="width: 100%">
                    <tr>
                        <td style="text-align: center; color:#fff; padding: 10px;">
                            Ikuti Kami / Follow us
                        </td>
                    </tr>
                    <tr style="text-align: center;">
                        <td style="display: inline-flex;">
                            <a href="https://www.facebook.com/GomodoTechnologies"><img src="{{ asset('img/facebook-icon.png') }}" alt="" width="20" style="padding: 10px;"></a>
                            <a href="https://www.instagram.com/gomodotech/"><img src="{{ asset('img/instagram-alt.png') }}" alt="" width="20" style="padding: 10px;"></a>
                            <a href="https://twitter.com/gomodotech?lang=en"><img src="{{ asset('img/twitter.png') }}" alt="" width="20" style="padding: 10px;"></a>
                        </td>
                    </tr>
                    <tr style="text-align: center;">
                        <td style="padding: 15px;">
                            <a href="https://mygomodo.com" style="background-color: #f3f4f4;padding: 8px 45px; font-size: 10px;border-radius: 4px;line-height: 1.71;text-decoration: none; color: #175d92;">MYGOMODO.COM</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>