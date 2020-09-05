<template>
    <div>
        <base-input alternative=""
            label="Nama Pengeluaran"
            placeholder="Nama Pengeluaran"
            input-classes="form-control-alternative"
            v-model="formRecord.name"
        />

        <base-input
            alternative=""
            label="Total"
            placeholder="Total"
            type="number"
            input-classes="form-control-alternative"
            v-model="formRecord.total"
        />
        <base-input 
            alternative=""
            label="Information"
            input-classes="form-control-alternative"
            placeholder="Information"
            v-model="formRecord.information"
        />
        <!-- todo buat component untuk get img -->
        <div v-if="isEditing==true" >
            <img :src=" 'http://localhost:8000/img/original/' + formRecord.img" alt="img_edit" height="100">
            <br><br>
        </div>
        <base-input label="">
            <input type="file" class="form-control form-control-alternative" name="" id="" @change="selected" >
        </base-input>
    </div>
</template>
<script>
import { mapGetters } from 'vuex';
import Swal from 'sweetalert2'

export default {
    props: ['formRecord'],
    methods: {
        selected(e) {
            const file = e.target.files[0];
            const reader = new FileReader();
            if(file['size'] < 2111775) {
                reader.onloadend = () => {
                    this.formRecord.img = reader.result
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
    },
    computed : {
        ...mapGetters({
            isEditing : 'isEditing'
        })
    }
}
</script>