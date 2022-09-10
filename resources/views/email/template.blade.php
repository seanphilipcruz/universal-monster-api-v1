<h4>{{ $env == 'dev' ? '[Test Message From Website]' : '[Forwarded Message From Website]'}}</h4>
<p>
    <strong>Sender: </strong>
    {{ $sender }}
</p>
<p>
    <strong>Contact No: </strong>
    {{ $number }}
</p>
<p>
    <strong>Email: </strong>
    {{ $email }}
</p>

<br>

<div>
    <p>{!! nl2br($content) !!}</p>
</div>

<br>

<h4>For contacts, questions and suggestions</h4>
<p>
    <b>Local No:</b>
    161
</p>
<p>
    <b>Email:</b>
    info@rx931.com
</p>
