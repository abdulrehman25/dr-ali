<table width="100%" style="text-align: center;margin: 50px 0;" cellpadding="10">
    <tr>
        <td>Hi {{ $user->first_name }}, your report is ready.</td>
    </tr>
    <tr>
        <td> 
		<img src="{{ asset('image/pdf-icon.png') }}" width="70px" />
		</td>
    </tr>
    <tr>
        <td>Dr. med. Ali Rahman</td>
    </tr>
    <tr>
        <td><img src="{{ asset('image/signature-img.png') }}" alt="" /></td>
    </tr>
    <tr>
        <td>payment information</td>
    </tr>
    <tr>
        <td>{{$package->price}} EUR</td>
    </tr>
    <tr>
        <td><a href="{{$link}}" target="_blank"><button style="color: #fff;
    background-color: rgba(0, 0, 0, 0); background-color: #427337;border-radius: 25px;padding: 5px 20px; font-size: 18px;
    font-weight: 600;line-height: 28px;border: 0;transition: all .45s cubic-bezier(.25, .46, .45, .94);cursor: pointer;">Proceed with payment</a></td>
    </tr>
</table>
