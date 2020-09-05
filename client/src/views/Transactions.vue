<template>
    <div>
        <crud-layout
            :columns="columns"
            :modelName="modelName"
            :formRecord="form"
            :formComponent="formComponent"
        >
            <div class="col-lg-6" slot="stats">
                <stats-card title="Transaksi Bulan ini"
                            type="gradient-green"
                            :sub-title="totalSpendThisMonth"
                            icon="ni ni-money-coins"
                            class="mb-4 mb-xl-0"
                >
                    <template slot="footer">
                        <span class="text-danger mr-2">{{sumTotalBayar}}</span>
                        <span class="text-nowrap">Pelanggan Belum Bayar</span>
                    </template>
                </stats-card>
            </div>
            <div slot="filter" class="col-md-6">
                <label>Filer Transaksi</label>
                <base-input addon-left-icon="ni ni-calendar-grid-58">
                    <flat-pickr slot-scope="{focus}"
                        @on-open="focus"
                        @on-close="filterDate"
                        :config="{allowInput: true, mode: 'range'}"
                        class="form-control datepicker"
                        v-model="dates.range">
                    </flat-pickr>
                </base-input>
            </div>
            <template slot="table-rows">
                <tr v-for="(transaction, index) in transactions" :key="transaction.id">
                    <td>
                        {{ index + 1 }}
                    </td>
                    <td>{{ transaction.code }}</td>
                    <td>{{ transaction.customer.code }} &ndash; {{ transaction.customer.name }}</td>
                    <td>{{ transaction.user.name }}</td>
                    <td>{{ transaction.indicator_total }}</td>
                    <td>{{ transaction.price | currency }}</td>
                    <td>
                        <badge tag="a" href="#" v-if="transaction.status === 'Dibayar'" type="success">
                            {{ transaction.status }}
                        </badge>
                        <badge tag="a" href="#" v-else type="warning">
                            {{ transaction.status }}
                        </badge>
                    </td>
                    <td>{{ transaction.created_at }}</td>
                    <td>
                        <action-button
                            :formRecord="form"
                            :record="transaction"
                            :modelName="modelName"
                        ></action-button>
                    </td>
                </tr>
            </template>
        </crud-layout>
    </div>
</template>

<script>
import TransactionForm from '../forms/TransactionForm'
import { Form } from 'vform'
import { mapGetters } from 'vuex'
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

export default {
    data: () => ({
        columns: [
            '#', 'kode', 'pelanggan', 'user', 'jumlah meteran', 'harga', 'status', 'dibuat tanggal'
        ],
        formComponent: TransactionForm,
        form: new Form({
            id: '',
            code: '',
            customer_id: '',
            user_id: '',
            indicator_total: '',
            price: '',
            status: '',
        }),
        modelName: 'transactions',
        dates: {
            range: new Date()
        }
    }),

    computed: {
        ...mapGetters([
            'transactions'
        ]),
        totalSpendThisMonth(){
            let hasil = 0
            this.transactions.forEach(val => {
                hasil += val.price
            })
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(hasil)
        },
        sumTotalBayar(){
            let total = this.transactions.filter(e => e.status === 'Belum Dibayar');

            return total.length
        }
    },
    components: {
        flatPickr
    },
    methods: {
        async getTotal(arr) {
            return await arr.reduce((a, b) => a.price + b.price)
        },
        filterDate(selectedDates, dateStr) {
          this.$store.dispatch('filterDate', {
              modelName: 'filterDate',
              filter: dateStr
          })
        },
    },
}
</script>