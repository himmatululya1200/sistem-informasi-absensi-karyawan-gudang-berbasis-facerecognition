<?php
$page = "Data User";
require_once("./header.php");
?>
<style>
  /* CSS untuk modal */
  .modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 100px;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0, 0, 0);
    background-color: rgba(0, 0, 0, 0.4);
  }

  .modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
  }

  .butn {
    padding: 10px 20px;
    color: white;
    background-color: #007bff;
    border: none;
    cursor: pointer;
  }

  .butn-danger {
    background-color: #dc3545;
  }

  .butn-primary {
    background-color: #007bff;
  }

  .butn:hover {
    opacity: 0.8;
  }

  .form-control {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ccc;
    box-sizing: border-box;
  }

  .custom-select {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ccc;
    box-sizing: border-box;
  }
</style>
<div id="layoutSidenav_content">
  <main>
    <div class="container-fluid">
      <h1 class="mt-4">Data User</h1>
      <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Data User</li>
      </ol>
      <!-- END MESSAGE -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <div>
            <i class="fas fa-table mr-1"></i>
            Data User
          </div>
          <div>
            <button type="button" class="btn btn-sm btn-primary" id="openModalBtn" data-toggle="modal" data-target="#modal-lg">
              Tambah Data User
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Username</th>
                  <th>Level</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tbody>
                <?php
                $no = 0;
                $query = mysqli_query($koneksi, "SELECT * FROM admin");
                while ($nama = mysqli_fetch_array($query)) {
                  $no++
                ?>
                  <tr>
                    <td width='5%'><?php echo $no; ?></td>
                    <td><?php echo $nama['admin_username']; ?></td>
                    <td><?php echo $nama['admin_nama']; ?></td>
                    <td class="text-center">
                      <a href="./edit_data.php?page=Data User&& admin_id=<?php echo $nama['admin_id']; ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                      <a onclick=" hapus_data(<?php echo $nama['admin_id']; ?>)" class="btn btn-danger text-white"> <i class="fas fa-trash"></i> Hapus</a>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>
  <?php
    require_once("./footer.php");
    ?>
</div>
<!-- Modal -->
<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close"></span>
    <h4>Tambah Data</h4>
    <form method="get" action="user/tambah_user.php">
      <div class="modal-body">
        <div class="row g-3">
          <div class="col">
            <input type="text" class="form-control" placeholder="username" aria-label="nama" name="admin_username" required>
          </div>
          <div class="col">
            <input type="password" class="form-control" placeholder="Password" aria-label="password" name="admin_password" required>
          </div>
          <div class="col">
            <select class="custom-select" id="inputGroupSelect01" name="admin_nama" required>
              <option selected>Pilih ...</option>
              <option value="Administrator">Administrator</option>
              <option value="User">User</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="butn butn-danger" id="closeModalBtn">Tutup</button>
        <button type="submit" class="butn butn-primary">Menyimpan</button>
      </div>
    </form>
  </div>
</div>

<!-- JavaScript -->
<script>
  // Get the modal
  var modal = document.getElementById("myModal");

  // Get the button that opens the modal
  var btn = document.getElementById("openModalBtn");

  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];

  // Get the close button inside the modal
  var closeModalBtn = document.getElementById("closeModalBtn");

  // When the user clicks the button, open the modal
  btn.onclick = function() {
    modal.style.display = "block";
  }

  // When the user clicks the close button inside the modal, close the modal
  closeModalBtn.onclick = function() {
    modal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function hapus_data(data_id) {
    // alert('ok');
    // window.location=("delete/hapus_data.php?id="+data_id);
    Swal.fire({
      title: "Apakah Data Ini Akan Dihapus?",
      // showDenyButton: false,
      showCancelButton: true,
      confirmButtonText: "Hapus Data",
      confirmButtonColor: "#ffc0cb"
      // denyButtonText: `Don't save`
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        window.location = ("user/hapus_user.php?admin_id=" + data_id);
      }
    });
  }
  
</script>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                'pdfHtml5'
            ]
        });
    });
</script>