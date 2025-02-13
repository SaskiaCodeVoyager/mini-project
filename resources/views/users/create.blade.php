

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
      <!-- Custom fonts for this template-->
      <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
      <link
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">
  
      <!-- Custom styles for this template-->
      <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
  
</head>
<body>
    

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Pendaftaran User - Langkah <span id="stepNumber">1</span></h4>
        </div>
        <div class="card-body">
            <form id="userForm" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Langkah 1: Akun -->
                <div id="step1">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role">
                            <option value="member">Member</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary nextStep">Selanjutnya</button>
                </div>

                <!-- Langkah 2: Data Tambahan -->
                <div id="step2" style="display: none;">
                    <div class="mb-3">
                        <label for="asal_sekolah" class="form-label">Asal Sekolah</label>
                        <input type="text" class="form-control" id="asal_sekolah" name="asal_sekolah">
                    </div>
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat">
                    </div>
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp">
                    </div>
                    <div class="mb-3">
                        <label for="foto_pribadi" class="form-label">Foto Pribadi</label>
                        <input type="file" class="form-control" id="foto_pribadi" name="foto_pribadi">
                    </div>
                    <button type="button" class="btn btn-secondary prevStep">Kembali</button>
                    <button type="submit" class="btn btn-success">Daftar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript untuk Multi-Step -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const step1 = document.getElementById("step1");
    const step2 = document.getElementById("step2");
    const stepNumber = document.getElementById("stepNumber");
    
    document.querySelector(".nextStep").addEventListener("click", function() {
        step1.style.display = "none";
        step2.style.display = "block";
        stepNumber.innerText = "2";
    });

    document.querySelector(".prevStep").addEventListener("click", function() {
        step2.style.display = "none";
        step1.style.display = "block";
        stepNumber.innerText = "1";
    });
});
</script>
</body>
</html>