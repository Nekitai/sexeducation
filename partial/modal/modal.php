<?php
$login_error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
$register_error = isset($_SESSION['register_error']) ? $_SESSION['register_error'] : '';
$active_form = isset($_SESSION['active_form']) ? $_SESSION['active_form'] : '';

// Hapus session error setelah ditampilkan
unset($_SESSION['login_error'], $_SESSION['register_error'], $_SESSION['active_form']);
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    * {
        font-family: 'Poppins', sans-serif;
        box-sizing: border-box;
    }
    .modal-content {
  border-radius: 10px 0px 30px 0px; /* Membuat sudut lebih bulat */
  background: transparent;
  backdrop-filter: blur(10px);
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
  border: 2px;
  color: white;
}

.h2, .h5 {
  color: white; /* Warna teks judul */
  text-align: center;
  background-position: center;
}

.modal-header {
  background-color: transparent; /* Warna latar belakang header */
  border-bottom: 1px solid #ddd; /* Garis bawah header */
}

.custom-btn {
   width: 100%;
   height: 40px;
   background-color: white;
   border: none;
   outline: none;
   border-radius: 40px;
   box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
   cursor: pointer;
   font-size: 16px;
   font-weight: 600;
   background-color: #f0f0f0;
    color: #000; 
}
.form-control {
    background: transparent;
    height: 40px;
    width: 100%;
    border: none;
    outline: none;
    border: 2px solid rgba(255, 255, 255, 0.5);
    border-radius: 40px;
    font-size: 16px;
    color: white;
    padding: 20px 45px 20px 20px;
}
.form-control:focus {
    border-color: #fff;
    box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
}
.modal-body input::placeholder {
color: #ddd;
}
.modal-header .btn-close {
    background-color: white;
}

/* Optional: Untuk memberikan jarak antar elemen */
.custom-mb-3 {
        margin-bottom: 1rem;
}
</style>
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h5" id="loginModalLabel">Login / Daftar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Login -->
                    <form id="loginForm" style="display: block;">
                        <input type="hidden" name="aksi" value="login">
                        <h2 class="h2">Login</h2>
                        <div class="custom-mb-3">
                            <label for="username" class="form-label">Nama Pengguna</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="custom-mb-3">
                            <label for="password" class="form-label">Sandi</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="custom-btn btn-primary">Login</button>
                        <a href="#" id="showRegisterLink">Belum punya akun? Daftar</a>
                    </form>

                    <!-- Form Register -->
                    <form id="registerForm" style="display: none;">
                        <input type="hidden" name="aksi" value="daftar">
                        <h2 class="h2">Daftar</h2>
                        <div class="custom-mb-3">
                            <label for="usernameRegister" class="form-label">Nama Pengguna</label>
                            <input type="text" class="form-control" id="usernameRegister" name="username" required>
                        </div>
                        <div class="custom-mb-3">
                            <label for="passwordRegister" class="form-label">Sandi</label>
                            <input type="password" class="form-control" id="passwordRegister" name="password" required>
                        </div>
                        <div class="custom-mb-3">
                            <label for="repassword" class="form-label">Konfirmasi Sandi</label>
                            <input type="password" class="form-control" id="repassword" name="repassword" required>
                        </div>
                        <button type="submit" class="custom-btn btn-primary">Daftar</button>
                        <a href="#" id="showLoginLink">Sudah punya akun? Login</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const showRegisterLink = document.getElementById('showRegisterLink');
        const showLoginLink = document.getElementById('showLoginLink');

        // Navigasi form
        showRegisterLink.addEventListener('click', function (e) {
            e.preventDefault();
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
        });

        showLoginLink.addEventListener('click', function (e) {
            e.preventDefault();
            registerForm.style.display = 'none';
            loginForm.style.display = 'block';
        });

        // Submit Login
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(loginForm);
            fetch('action/proses.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: data.status === "success" ? "Berhasil!" : "Gagal!",
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false,
                    icon: data.status,
                }).then(() => {
                    if (data.redirect) window.location.href = data.redirect;
                });
            });
        });

        // Submit Register
        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(registerForm);
            fetch('action/proses.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: data.status === "success" ? "Berhasil!" : "Gagal!",
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false,
                    icon: data.status,
                }).then(() => {
                    if (data.redirect) window.location.href = data.redirect;
                });
            });
        });
    </script>





