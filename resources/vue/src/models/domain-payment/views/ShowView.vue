<template>

	<div v-if="dataLoaded">

		<breadcrumb-component :items="items" />
	    
	    <div class="uk-container uk-container-expand">

	    	<div class="uk-grid-small" uk-grid>
	    		
	    		<div class="uk-width-1-3@m uk-width-1-1@s">

					<model-card 
						:domain-payment="domainPayment" />

	    		</div>

	    		<div class="uk-width-expand uk-width-1-2@m uk-width-1-1@s">

	    			<div v-if="this.isShowView">

	    				<model-profile 
	    					:domain-payment="domainPayment" />
	    				
	    			</div>

	    			<div v-else>
	    				
	    				<router-view @updateData="fetchData"></router-view>

	    			</div>

	    		</div>

	    	</div>

	    </div>

	</div>

</template>

<script>

	import { showModel } from '@domainModels/domain-payment'
	import ModelCard from '@domainModels/domain-payment/widgets/ModelCard.vue'
	import ModelProfile from '@domainModels/domain-payment/widgets/ModelProfile.vue'

	export default {

		components: {

			ModelCard,

			ModelProfile

		},

		mounted() {

			this.fetchData();

		},

		data() {
		
			return {

				dataLoaded: false,

				title: undefined,

				domainPaymentId: this.$route.params.id,

				domainPayment: {},

			}
		
		},

		computed: {

			isShowView() {

				return (this.$route.name == 'AdminShowDomainPayment');

			},

			items() {

				if(this.$route.name == 'AdminShowDomainPayment') {

					return [
						{ text: 'DomainPayments', path: '/admin/domain-payment'},
						{ text: this.domain-payment.name ?? 'DomainPayment', path: '/admin/domain-payment/' + this.domain-payment.id}
					];

				} else if(this.$route.name == 'AdminEditDomainPayment') {

					return [
						{ text: 'DomainPayments', path: '/admin/domain-payment'},
						{ text: this.domain-payment.name ?? 'DomainPayment' , path: '/admin/domain-payment/' + this.domain-payment.id},
						{ text: 'Editar domain-payment', path: '/admin/domain-payment/' + this.domain-payment.id + '/edit'}	
					];

				}

			}

		},

		methods: {

			async fetchData() {

				await this.fetchDomainPayment()

				this.dataLoaded = true;
				
				this.title = this.domainPayment.name;

				document.title = this.title;

			},

			async fetchDomainPayment() {

				let res = await showModel(this.domainPaymentId);

				this.domainPayment = res;

            },

		}

	}

</script>