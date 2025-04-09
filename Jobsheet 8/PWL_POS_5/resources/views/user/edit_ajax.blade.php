@empty($user)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5> Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    @php
        $url = $user->foto ? asset('storage/foto/' . $user->foto) : asset('images/default.png');
    @endphp

    <form action="{{ url('/user/' . $user->user_id . '/update_ajax') }}" method="POST" id="form-edit" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Level Pengguna</label>
                        <select name="level_id" id="level_id" class="form-control" required>
                            <option value="">- Pilih Level -</option>
                            @foreach ($level as $l)
                                <option {{ $l->level_id == $user->level_id ? 'selected' : '' }} value="{{ $l->level_id }}">
                                    {{ $l->level_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-level_id" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input value="{{ $user->username }}" type="text" name="username" id="username" class="form-control" required>
                        <small id="error-username" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Nama</label>
                        <input value="{{ $user->nama }}" type="text" name="nama" id="nama" class="form-control" required>
                        <small id="error-nama" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Abaikan jika tidak ingin ubah password">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small id="error-password" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Foto Profil</label>
                        <input type="file" name="foto" id="foto" class="form-control">
                        <small id="error-foto" class="error-text text-danger"></small>
                        <div class="mt-2">
                            <img src="{{ $url }}" width="120" class="img-thumbnail">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function () {
            // Toggle show/hide password
            $('.toggle-password').on('click', function () {
                const passwordInput = $('#password');
                const icon = $(this).find('i');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                icon.toggleClass('fa-eye fa-eye-slash');
            });

            // Form validation
            $("#form-edit").validate({
                rules: {
                    level_id: { required: true, number: true },
                    username: { required: true, minlength: 3, maxlength: 20 },
                    nama: { required: true, minlength: 3, maxlength: 100 },
                    password: { minlength: 6, maxlength: 20 },
                    foto: { extension: "jpg|jpeg|png" }
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataUser.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function (prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
