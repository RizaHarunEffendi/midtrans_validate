<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-RCi4DCru_tETIRkZ"></script>
    <script src="<?= base_url('asset/js/jquery.min.js'); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <title>Pembayaran SPP</title>
    <style>
    label.error {
        color: red;
        font-weight: 500;
        display: block;
        font-size: 14px;
    }

    .c1 {
        border: 1px red ridge;
    }

    /* small.text-danger {
        font-size: 14px;
        color: red;
        font-weight: 500;
    } */
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Aplikasi Pembayaran SPP</h2>
        <form id="payment-form" method="post" action="<?=site_url()?>/snap/finish">
            <input type="hidden" name="result_type" id="result-type" value="">
            <input type="hidden" name="result_data" id="result-data" value="">
            <div class="form-group">
                <label for="">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Kelas</label>
                <select class="form-control" name="kelas" id="kelas">
                    <option value="VII">VII</option>
                    <option value="VIII">VIII</option>
                    <option value="XI">XI</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Jumlah Bayar</label>
                <input type="text" name="jmlbayar" id="jmlbayar" class="form-control">
            </div>
            <button class="btn btn-primary" id="pay-button">Bayar</button>
        </form>
    </div>

    <script type="text/javascript">
    $(document).ready(function() {
        $("#payment-form").validate({
            rules: {
                nama: {
                    required: true,
                },
                kelas: {
                    required: true,
                },
                jmlbayar: {
                    required: true,
                },
            },
            messages: {
                nama: {
                    required: "Nama harus di isi",
                },
                kelas: {
                    required: "Kelas harus di isi",
                },
                jmlbayar: {
                    required: "Jumlah Bayar harus di isi",
                },
            },
            highlight: function(element) {
                $(element).addClass("c1");
            },
            unhighlight: function(element) {
                $(element).removeClass("c1");
            },
        });
    });
    $('#pay-button').click(function(event) {
        // var IsValid = $("#payment-form").valid();
        event.preventDefault();
        // $(this).attr("disabled", "disabled");
        var formData = {
            nama: $("#nama").val(),
            kelas: $("#kelas").val(),
            jmlbayar: $("#jmlbayar").val(),
        }
        var IsValid = $("#payment-form").valid();
        if (IsValid) {
            $.ajax({
                type: 'POST',
                url: '<?= base_url('snap/token'); ?>',
                data: formData,
                cache: false,

                success: function(data) {
                    //location = data;

                    console.log('token = ' + data);

                    var resultType = document.getElementById('result-type');
                    var resultData = document.getElementById('result-data');

                    function changeResult(type, data) {
                        $("#result-type").val(type);
                        $("#result-data").val(JSON.stringify(data));
                        //resultType.innerHTML = type;
                        //resultData.innerHTML = JSON.stringify(data);
                    }

                    snap.pay(data, {

                        onSuccess: function(result) {
                            changeResult('success', result);
                            console.log(result.status_message);
                            console.log(result);
                            $("#payment-form").submit();
                        },
                        onPending: function(result) {
                            changeResult('pending', result);
                            console.log(result.status_message);
                            $("#payment-form").submit();
                        },
                        onError: function(result) {
                            changeResult('error', result);
                            console.log(result.status_message);
                            $("#payment-form").submit();
                        }
                    });
                }
            });
        }
    });
    </script>

</body>

</html>