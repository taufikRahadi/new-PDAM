<template>
    <div>
        <base-button type="primary" @click="editButtonAction()">
            <i class="ni ni-active-40"></i>
        </base-button>
        <base-button type="danger" v-show="buttonState" @click="deleteButtonAction()">
            <i class="ni ni-fat-remove"></i>
        </base-button>
        <slot></slot>
    </div>
</template>

<script>
import Swal from 'sweetalert2'

export default {
    props: [
        'modelName',
        'formRecord',
        'record',
        'modals'
    ],

    computed: {
        buttonState() {
            if(this.modelName === 'users') {
                if(this.record.id === this.$store.state.currentUser.id) {
                    return false
                }
            }

            return true
        }
    },

    methods: {
        editButtonAction() {
            this.$store.commit('setIsEditing', true)
            this.$store.commit('setModals')
            this.formRecord.fill(this.record)
        },

        deleteButtonAction() {
            Swal.fire({
                title: 'Yakin ingin hapus data?',
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    this.$store.dispatch('destroyData', {
                        modelName: this.modelName,
                        id: this.record.id
                    })
                        .then(() => {
                            Swal.fire(
                                'Sukses',
                                'Data berhasil dihapus',
                                'success'
                            )
                        })
                        .catch(() => {
                            Swal.fire(
                                'Gagal',
                                'Data gagal dihapus',
                                'error'
                            )
                        })
                }
            })
        }
    }
}
</script>