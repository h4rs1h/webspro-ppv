@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }} </h1>
        </div>
    </div>
    @if (session()->has('success'))
        <div class="alert alert-success col-lg-12" role="alert">
        {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Scand QR Code
                        </div>
                        <div class="panel-body">
							
							 <img src="data:image/png;base64,{{ $img  }}" alt="QRCODE" /><br>
                           
                            <progress value="0" max="20" id="progressBar"></progress><br>
                            Lakukan Scan Sebelum Session Berakhir.
                            <a href="/admin/wa/setting?wablast=statusqr" class="btn btn-primary">Cek Status Koneksi</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var timeleft = 20;
    var downloadTimer = setInterval(function(){
    if(timeleft <= 0){
        clearInterval(downloadTimer);
    }
    document.getElementById("progressBar").value = 20 - timeleft;
    timeleft -= 1;
    }, 1000);

</script>
@endsection
