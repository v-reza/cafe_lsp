<li class="nav-item">
    <span class="nav-link" id="historyTransaksi" data-bs-toggle="modal" data-bs-target="#historyModal" style="cursor: pointer"><i class="fas fa-history"></i>&nbsp;Riwayat Transaksi</span>
</li>

<!-- Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="historyModalLabel">Riwayat Transaksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row" id="riwayatTransaksi">
                {{-- <span id="indexTrx">asda</span> --}}
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
