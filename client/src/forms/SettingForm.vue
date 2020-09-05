<template>
    <div>
        <base-input alternative=""
            label="Nama Pengeluaran"
            placeholder="Harga"
            input-classes="form-control-alternative"
            v-model="formRecord.harga"
        />
        <!-- todo buat component untuk get img -->
        <!-- <div>
            <img :src=" 'http://localhost:8000/img/logo/' + formRecord.logo" alt="img_edit" width="100" height="100">
            <br><br>
        </div> -->
        <base-input label="">
            <input type="file" class="form-control form-control-alternative" name="" id="" @change="selected" >
        </base-input>
    </div>
</template>
<script>
import Swal from 'sweetalert2'

export default {
    props: ['formRecord'],
    methods: {
        selected(e) {
            const file = e.target.files[0];
            const reader = new FileReader();
            if(file['size'] < 2111775) {
                reader.onloadend = () => {
                    this.formRecord.logo = reader.result
                }
                reader.readAsDataURL(file)
            } else {
                Swal.fire(
                    'Error',
                    'Ukuran Gambar Tidak Boleh Lebih Dari 2MB',
                    'error'
                )
            }
        }
    }
}
</script>