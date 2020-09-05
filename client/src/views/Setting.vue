<template>
    <div>
        <base-header type="gradient-success" class="pb-6 pb-8 pt-5 pt-md-8">
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <stats-card title="Harga Permeter"
                        type="gradient-red"
                        :sub-title="harga"
                        icon="ni ni-active-40"
                        class="mb-4 mb-xl-0"
                    >
                    </stats-card>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <stats-card title="Logo"
                        type="gradient-red"
                        
                        icon="ni ni-active-40"
                        class="mb-4 mb-xl-0"
                    >
                    <img :src="getImage('img/logo',setting.logo)" width="100">
                    </stats-card>
                </div>
            </div>
            <base-button class="float-left mb-3" type="primary" @click="editForm">
                Edit
            </base-button>
        </base-header>
        <div>
            <modal :show.sync="$store.state.modals">
                <form @submit.prevent="submitForm()" enctype="multipart/form-data">
                    <SettingForm :formRecord="form"></SettingForm>
                </form>
                <template slot="footer">
                    <base-button type="primary" :disabled="isDisabled" @click="submitForm()">Save changes</base-button>
                    <base-button type="link" class="ml-auto" @click="$store.commit('setModals')">Close</base-button>
                </template>
            </modal>
        </div>
    </div>
    
</template>

<script>
import SettingForm from '../forms/SettingForm'
import Swal from 'sweetalert2'
import { Form } from 'vform'
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        form: new Form({
            id: '',
            logo: '',
            harga: '',
        }),
        modelName: 'setting',
        formComponent: SettingForm,
        isDisabled: false,
    }),
    created(){
        this.$store.dispatch('fetchData', this.modelName)
    },
    computed: {
        ...mapGetters({
            setting: 'setting'
        }),

        harga() {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(this.setting.harga)
        }
    },
    methods: {
        editForm() {
            this.$store.commit('setModals')
            this.form.fill(this.setting)
        },
        submitForm() {
            this.$store.dispatch('updateData', {
                form: this.form,
                modelName: this.modelName,
                id: this.form.id
            }).then(() => {
                this.isDisabled = false
                this.$store.commit('setModals')
                Swal.fire(
                    'Sukses',
                    'Data berhasil di update',
                    'success'
                )
            })
            .catch(() => {
                this.isDisabled = false
                Swal.fire(
                    'Gagal',
                    'Data gagal di update',
                    'error'
                )
            })
        }
    },
    components:{
        SettingForm: SettingForm
    },
}
</script>