<template>
    <div>
        <div v-if="!isEditing">
            <base-input
                alternative=""
                label="Pelanggan"
            >
            <select class="form-control form-control-alternative" v-model="formRecord.customer_id" @change="select($event)" >
                <option value="">Pilih Pelanggan</option>
                <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                    {{ customer.code }} - {{ customer.name }}
                </option>
            </select>
            </base-input>
        
            <base-input 
                type="number"
                alternative=""
                label="Input Meteran"
                v-model="formRecord.indicator_total"
                input-classes="form-control-alternative"
                placeholder="Masukkan Jumlah Meteran"
                @keyup="calculate($event)"
            />
            <div v-if="transaction.length != 0">
                <div class="row">
                    <div class="col-6">
                        <base-input 
                            type="text"
                            alternative=""
                            label="nama customer"
                            input-classes="form-control-alternative"
                            placeholder=""
                            readonly
                            v-model="transaction.customer.name"
                        />
                    </div>
                    <div class="col-6">
                        <base-input 
                            type="number"
                            alternative=""
                            label="jumlah meteran bulan lalu"
                            input-classes="form-control-alternative"
                            placeholder=""
                            readonly
                            v-model="transaction.indicator_total"
                        />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <base-input 
                            type="text"
                            alternative=""
                            label="Harga permeter"
                            input-classes="form-control-alternative"
                            placeholder=""
                            readonly
                            v-model="setting.harga"
                        />
                    </div>
                    <div class="col-6">
                        <base-input 
                            type="number"
                            alternative=""
                            label="Meteran yang harus dibayar"
                            input-classes="form-control-alternative"
                            placeholder=""
                            readonly
                            v-model="jmlMeter"
                        />
                    </div>
                </div>
                <base-input 
                    type="number"
                    alternative=""
                    label="Jumlah uang yang harus dibayar"
                    input-classes="form-control-alternative"
                    placeholder=""
                    readonly
                    v-model="formRecord.price"
                />
            </div>
        </div>
        <base-input 
            alternative=""
            label="Status"
        >
            <select v-model="formRecord.status" class="form-control form-control-alternative">
                <option value="">Status Transaksi</option>
                <option v-for="stats in status" :key="stats" :value="stats">{{ stats }}</option>
            </select>
        </base-input>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    props: [
        'formRecord'
    ],

    data: () => ({
        status: [
            'Dibayar', 'Belum Dibayar'
        ],
        transaction : [],
        jmlMeter : 0,
    }),

    methods: {
        fetchFormData() {
            this.$store.dispatch('fetchData', 'customers')
            this.$store.dispatch('fetchData', 'users')
        },
        select(event) {
            axios.get('getTransaction/'+event.target.value)
                .then( res => {
                    this.transaction = res.data.data
                })
                .catch( err => {
                    alert(err);
                })
        },
        calculate(event) {
            this.jmlMeter = parseInt(event.target.value) - parseInt(this.transaction.indicator_total)
            this.formRecord.price = this.setting.harga * this.jmlMeter
        }
    },

    computed: {
        ...mapGetters({
            customers: 'customers',
            users: 'users',
            setting: 'setting',
            isEditing: 'isEditing'
        })
    },

    created() {
        this.fetchFormData()
    }
}
</script>