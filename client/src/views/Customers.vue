<template>
    <div>
        <crud-layout
            :columns="columns"
            :modelName="modelName"
            :formRecord="form"
            :formComponent="formComponent"
        >
            <div class="col-lg-5" slot="stats">
                <stats-card title="Total Pelanggan"
                            type="gradient-green"
                            :sub-title="customers.length.toString()"
                            icon="ni ni-money-coins"
                            class="mb-4 mb-xl-0"
                >
                </stats-card>
            </div>
            <template slot="table-rows">
                <tr v-for="(customer, index) in customers" :key="customer.id">
                    <td>
                        {{ index + 1 }}
                    </td>
                    <td>
                        {{ customer.code}}
                    </td>
                    <td>
                        {{ customer.name}}
                    </td>
                    <td>
                        {{ customer.phone}}
                    </td>
                    <td>
                        {{ customer.user.name}}
                    </td>
                    <td>
                        {{customer.address}}
                    </td>
                    <td>
                        <action-button 
                            :formRecord="form"
                            :record="customer"
                            :modelName="modelName"
                        />
                    </td>
                </tr>
            </template>


        </crud-layout>        
    </div>
</template>

<script>
import CustomerForm from '../forms/CustomerForm'
import { Form } from 'vform'
import { mapGetters } from 'vuex'

export default {
    data: () => ({
        columns: [
            '#', 'Kode', 'Nama', 'No Hp', 'Diedit Oleh', 'Alamat',
        ],
        form: new Form({
            id: '',
            code: '',
            name: '',
            phone: '',
            address: '',
            user_id: '',
        }),
        formComponent: CustomerForm,
        modelName: 'customers'
    }),

    computed: {
        ...mapGetters({
            customers: 'customers'
        })
    },
}
</script>