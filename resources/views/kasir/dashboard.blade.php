@extends('master.home')
@section('title', 'Dashboard Kasir')
@section('icon')
<link rel="icon" href="https://cdn.icon-icons.com/icons2/1534/PNG/512/3338945-business-tools-cashier_106959.png" type="image/x-icon" />
@endsection
@section('content')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="/"><i class="fas fa-coffee"></i>&nbsp;Cafe Ayo Ngopi</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <span class="nav-link" id="makanan" style="cursor: pointer" aria-current="page" onclick="filterCategory('makanan')"><i class="fas fa-hotdog"></i>&nbsp;Makanan</span>
          </li>
          <li class="nav-item">
            <span class="nav-link" id="minuman" style="cursor: pointer" onclick="filterCategory('minuman')"><i class="fas fa-cocktail"></i>&nbsp;Minuman</span>
          </li>
          @include('kasir.riwayat.index')
        </ul>
        <form class="d-flex">
            <ul class="navbar-nav me-auto mb-4 mb-lg-0">
                <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-shopping-cart"></i>&nbsp;<span style="color: red" id="keranjang"></span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown" id="cart">
                <div class="d-flex justify-content-center" id="viewKeranjang"></div>
            </ul>
            </li>
          </ul>
            @include('kasir.checkout.index')
            <input class="form-control me-2" type="search" id="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-warning" type="button" onclick="logout()"><i class="fas fa-sign-out-alt"></i></button>&nbsp;
        </form>
      </div>
    </div>
  </nav><br>

  {{-- Produk Kasir --}}
  @include('kasir.produk.index')

@endsection
@section('script')
<script>
$(document).ready(function() {
    loadProduk()
    loadKeranjang()
    loadTotalPembayaran()
    loadRiwayatTransaksi()
    $("#search").keyup(function() {
        var produk = '';
        const keyword = $("#search").val()
        $("#minuman").removeClass('active')
        $("#makanan").removeClass('active')
        if (keyword.length == 0){
            loadProduk()
        } else {
            $.ajax({
                type: "GET",
                url: "/searchProduk/"+keyword,
                success: function (response) {
                    var data = response.data
                    console.log(data)
                    if (data.length == 0) {
                        produk+=`
                        <button class="btn btn-primary" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Sepertinya tidak ada data...
                        </button>`
                        $("#produk").html(produk)
                    } else {
                        $.each(data, function(i) {
                        produk+= `
                            <div class="col-md-4 mb-2">
                                <div class="card" style="width: 18rem;">
                                <img src="${data[i].gambar}" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">${data[i].nama}</h5>
                                    <p class="card-text">Harga : Rp. ${formatToNumber(data[i].harga)}</p>
                                    <span class="btn btn-primary" onclick="tambahPesanan(this)"
                                        data-id="${data[i].id}"
                                        data-nama="${data[i].nama}"
                                        data-stok="${data[i].stock}">Tambah Pesanan</span>
                                </div>
                                </div>
                            </div>
                            `
                         $("#produk").html(produk)
                        })
                    }
                }
            });
        }
    })
})


function loadProduk()
{
    var produk = ''
    $.ajax({
        type: "GET",
        url: "/renderProduk",
        success: function (response){
            var data = response.data
            $.each(data, function(i) {
                produk += `
                <div class="col-md-4 mb-2 mb-2">
                    <div class="card" style="width: 18rem;">
                    <img src="${data[i].gambar}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">${data[i].nama}</h5>
                        <p class="card-text">Harga : Rp. ${formatToNumber(data[i].harga)}</p>
                        <p class="card-text">Sisa Stock : ${data[i].stock}</p>
                        <span class="btn btn-primary" onclick="tambahPesanan(this)"
                            data-id="${data[i].id}"
                            data-nama="${data[i].nama}"
                            data-stok="${data[i].stock}">Tambah Pesanan</span>
                    </div>
                    </div>
                </div>
                `
                $("#produk").html(produk)
            })
        }
    });
}

function tambahPesanan(produk)
{
    const title = $(produk).data('nama')
    const produkId = $(produk).data('id')
    swal({
        title: 'Tambah Pesanan?',
        text: `Menu ${title}`,
        icon: 'warning',
        buttons: ["Batal", "Ya!"],
    }).then(function (tambah) {
        if(tambah) {
            $.ajax({
                type: "POST",
                url: "/addToCart",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "produk_id": produkId,
                },
                success: function (response) {
                    swal({
                        title: 'Sukses',
                        text: 'Berhasil menambahkan menu ke keranjang',
                        icon: 'success'
                    })
                    loadKeranjang()
                    loadTotalPembayaran()
                },
                error: function (response){
                    console.log("error: " + response)
                }
            });
        }
    })
}

function loadRiwayatTransaksi()
{
    let isiRiwayat = ''
    $.ajax({
        type: "GET",
        url: "/renderRiwayatTransaksi",
        success: function (response) {
            var data = response.data
            if (data.length == 0) {
                isiRiwayat += `
                    <div></div>
                `
                $("#riwayatTransaksi").html(isiRiwayat)
            } else {
                $.each(data, function(i, riwayat) {
                    $.each(riwayat.transaksi.keranjang, function(k, keranjang) {
                        var totHarga = data[i].transaksi.keranjang[k].produk.harga * data[i].transaksi.keranjang[k].stock
                        isiRiwayat += `
                        <div class="col-md-2">
                            <img
                            class="img-thumbnail"
                            src="${data[i].transaksi.keranjang[k].produk.gambar}" alt="">
                        </div>
                        <div class="col-md-6">
                            <h5>Detail Pesanan</h5>
                            <div class="row">
                                <span class="text-left" id="nama">Nama : ${data[i].transaksi.keranjang[k].produk.nama}</span>
                                <span class="text-left" id="nama">Jumlah : ${data[i].transaksi.keranjang[k].stock}</span>
                                <span class="text-left" id="nama">Harga / Satuan : Rp. ${formatToNumber(data[i].transaksi.keranjang[k].produk.harga)}</span>
                                <span class="text-left" id="nama">Total Harga : ${formatToNumber(totHarga)}</span>
                            </div>
                            </div>
                        <div class="col-md-4">
                            <h5>Detail Transaksi</h5>
                            <div class="row">
                                <span class="text-left" id="nama">Transaksi ID : ${data[i].transaksi.id}</span>
                                <span class="text-left" id="nama">Status : ${data[i].transaksi.status}</span>
                            </div>
                        </div>
                        `
                    })
                })
                $("#riwayatTransaksi").append(isiRiwayat)
            }
        }
    });
}

function loadKeranjang()
{
    let isiKeranjang = ''
    let isiPembayaran = ''
    $.ajax({
        type: "GET",
        url: "/renderKeranjang",
        success: function (response) {
            $('#keranjang').text(response.total)
            var data = response.keranjang
            if (data.length == 0){
                isiKeranjang += `
                    <li><span class="dropdown-item">Kosong</span></li>
                `
                isiPembayaran += `
                <div class="col-md-2"></div>
                `
                $("#cart").html(isiKeranjang)
                $("#pesanan").html(isiPembayaran)
                $("#checkout").hide()
                $("#bayarKeranjang").hide()
            } else {
                var sum = 0
                $("#bayarKeranjang").show()
                $.each(data, function(i) {
                    const totHarga = data[i].produk.harga * data[i].stock
                    isiKeranjang += `
                        <li><span class="dropdown-item"><img class="img-thumbnail" src="${data[i].produk.gambar}"></img></span></li>
                        <li><a class="dropdown-item">${data[i].produk.nama}</a></li>
                        <li><a class="dropdown-item">Jumlah: ${data[i].stock}</a></li>
                        <li><a class="dropdown-item">Harga: Rp. ${formatToNumber(totHarga)}</a></li>
                        <li><a class="dropdown-item"><span class="btn btn-danger" data-id="${data[i].id}"
                            data-nama="${data[i].produk.nama}" onclick="hapusPesanan(this)">Hapus pesanan</span></a></li>
                        <li><hr class="dropdown-divider"></li>
                    `
                    isiPembayaran += `
                    <div class="col-md-2">
                        <img
                        class="img-thumbnail"
                        src="${data[i].produk.gambar}" alt="">
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <span class="text-left" id="nama">Nama : ${data[i].produk.nama}</span>
                            <span class="text-left" id="jumlah">Jumlah: ${data[i].stock}</span>
                            <span class="text-left" id="hargaSatuan">Harga / Satuan: Rp. ${formatToNumber(data[i].produk.harga)}</span>
                            <span class="text-left" class="totalHarga" id="totalHarga">Total Harga: Rp. ${totHarga}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h4>Tambah Pesanan</h4>
                        <div class="row d-flex justify-content-start">
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-dark" data-id="${data[i].id}" onclick="tambahPesanModal(this)">+</button>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-dark" data-id="${data[i].id}" onclick="kurangPesanModal(this)">-</button>
                            </div>
                        </div>
                    </div>
                    `
                    $("#cart").html(isiKeranjang)
                    $("#pesanan").html(isiPembayaran)
                })
                $("#checkout").show()
            }

        }
    });
}

function kurangPesanModal(modal)
{
    const keranjangId = $(modal).data('id')
    swal({
        title: "Yakin ingin mengurangi pesan?",
        icon: 'warning',
        buttons: ["Batal", "Ya!"],
    }).then(function(tambah) {
        if(tambah) {
            $.ajax({
                type: "POST",
                url: "/kurangPesananModal",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "keranjangId": keranjangId
                },
                success: function (response) {
                    swal({
                        title: 'Sukses',
                        text: response.message,
                        icon: 'success'
                    })
                    loadKeranjang()
                    loadTotalPembayaran()
                }
            });
        }
    })
}

function tambahPesanModal(modal)
{
    const keranjangId = $(modal).data('id')
    swal({
        title: "Yakin ingin menambah pesan?",
        icon: 'warning',
        buttons: ["Batal", "Ya!"],
    }).then(function(tambah) {
        if(tambah) {
            $.ajax({
                type: "POST",
                url: "/addPesananModal",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "keranjangId": keranjangId
                },
                success: function (response) {
                    swal({
                        title: 'Sukses',
                        text: response.message,
                        icon: 'success'
                    })
                    loadKeranjang()
                    loadTotalPembayaran()
                }
            });
        }
    })
}

function loadTotalPembayaran()
{
    $.ajax({
        type: "GET",
        url: "/renderTotalPembayaran",
        success: function (response) {
            var data = response.data
            $("#totalHarusDibayar").text("Rp. " + formatToNumber(data.total_pembayaran))
        }
    });
}

function hapusPesanan(produk)
{
    const keranjangId = $(produk).data('id')
    console.log(keranjangId)
    const title = $(produk).data('nama')
    swal({
        title: 'Hapus Pesanan?',
        text: `Menu ${title}`,
        icon: 'warning',
        buttons: ["Batal", "Ya!"],
    }).then(function (hapus) {
        if(hapus) {
            $.ajax({
                type: "POST",
                url: "/deleteFromCart",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "keranjang_id": keranjangId,
                },
                success: function (response) {
                    swal({
                        title: 'Sukses',
                        text: response.message,
                        icon: 'success'
                    })
                    loadKeranjang()
                    loadTotalPembayaran()
                },
                error: function (response){
                    console.log("error: " + response)
                }
            });
        }
    })
}

function filterCategory(kategori)
{
    var produk = ''
    $.ajax({
        type: "GET",
        url: "/filterKategori/" + kategori,
        success: function (response) {
            var data = response.data
            $.each(data, function(i) {
                produk+= `
                <div class="col-md-4 mb-2">
                    <div class="card" style="width: 18rem;">
                    <img src="${data[i].gambar}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">${data[i].nama}</h5>
                        <p class="card-text">Harga : Rp. ${formatToNumber(data[i].harga)}</p>
                        <span class="btn btn-primary" onclick="tambahPesanan(this)"
                            data-id="${data[i].id}"
                            data-nama="${data[i].nama}"
                            data-stok="${data[i].stock}">Tambah Pesanan</span>
                    </div>
                    </div>
                </div>
                `
                $("#produk").html(produk)
            })
            if (kategori == 'makanan') {
                $("#minuman").removeClass('active')
                $("#makanan").addClass('active')
            } else if (kategori == 'minuman') {
                $("#makanan").removeClass('active')
                $("#minuman").addClass('active')
            }
        }
    });
}

function logout()
{
    swal({
        title: 'Yakin ingin keluar?',
        icon: 'warning',
        buttons: ["Batal", "Ya!"],
    }).then(function (logout) {
        if (logout) {
            $.ajax({
                type: "POST",
                url: "/logout",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                    location.replace('/login')
                }
            });
        }
    })
}

function bayar()
{
    let meja = ''
    var htmlContent = document.createElement("div");
    htmlContent.innerHTML = "<input type=\"text\" id=\"tunai\" class=\"form-control\" placeholder=\"Masukkan Tunai : Contoh 100000\" autocomplete=\"off\"><br><br><select class=\"form-select\" name=\"meja\" id=\"meja\" aria-label=\".form-select-sm example\"> <option selected>Pilih Meja</option></select>";
    $.ajax({
        type: "GET",
        url: "/renderMeja",
        success: function (response) {
            var data = response.data
            $.each(data, function(i) {
                meja += `
                    <option value="${data[i].meja}">Meja ${data[i].meja}</option>
                `
            })
            $("#meja").append(meja)
        }
    });

    swal({
        title: 'Bayar pesananmu sekarang!',
        text: 'Bayar pesananmu menggunakan tunai',
        icon: 'warning',
        content: htmlContent,
        buttons: ["Batal", "Bayar"],
    }).then(function (bayar) {
        const tunaiValue = $("#tunai").val()
        const pilihMeja = $('select[name=meja] option').filter(':selected').val()
        if(bayar) {
            if (pilihMeja == "Pilih Meja") {
                swal({
                    title: 'Terjadi Kesalahan',
                    text: 'Meja harus dipilih',
                    icon: 'error'
                })
            } else {
                $.ajax({
                    type: "POST",
                    url: "/addTransaksi",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "tunai": tunaiValue,
                        "meja": pilihMeja
                    },
                    success: function (response) {
                        swal({
                            title: 'Sukses',
                            text: response.message,
                            icon: 'success'
                        })
                        loadKeranjang()
                        loadTotalPembayaran()
                        loadProduk()
                        loadRiwayatTransaksi()
                    },
                    error: function (response) {
                        const msg = response.responseJSON.message
                        swal({
                            title: 'Terjadi Kesalahan',
                            text: msg,
                            icon: 'error'
                        })
                    }
                });

            }
        }
    })
}
</script>
@endsection
