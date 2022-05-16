<div class="d-flex justify-content-between">
    <button class="btn btn-success" type="button" id="checkout" data-bs-toggle="modal" data-bs-target="#exampleModal">Bayar</button>&nbsp;
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pembayaran</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row" id="pesanan">
          </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <span class="text-left" >Total yang harus dibayar: <span id="totalHarusDibayar"></span></span>
            </div>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="bayarKeranjang" onclick="bayar()">Bayar</button>
        </div>
      </div>
    </div>
  </div>
