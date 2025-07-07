function aksesDitolakSwal(){
    Swal.fire({
        icon: 'error',
        title: 'Akses Ditolak',
        text: 'Anda tidak memiliki hak untuk melakukan aksi ini!',
        showConfirmButton: true
    });
}
