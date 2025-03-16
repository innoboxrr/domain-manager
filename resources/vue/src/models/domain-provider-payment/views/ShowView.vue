<template>

	<div v-if="dataLoaded">

		<breadcrumb-component :items="items" />
	    
	    <div class="uk-container uk-container-expand">

	    	<div class="uk-grid-small" uk-grid>
	    		
	    		<div class="uk-width-1-3@m uk-width-1-1@s">

					<model-card 
						:domain-provider-payment="domainProviderPayment" />

	    		</div>

	    		<div class="uk-width-expand uk-width-1-2@m uk-width-1-1@s">

	    			<div v-if="this.isShowView">

	    				<model-profile 
	    					:domain-provider-payment="domainProviderPayment" />
	    				
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

	import { showModel } from '@domainModels/domain-provider-payment'
	import ModelCard from '@domainModels/domain-provider-payment/widgets/ModelCard.vue'
	import ModelProfile from '@domainModels/domain-provider-payment/widgets/ModelProfile.vue'

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

				domainProviderPaymentId: this.$route.params.id,

				domainProviderPayment: {},

			}
		
		},

		computed: {

			isShowView() {

				return (this.$route.name == 'AdminShowDomainProviderPayment');

			},

			items() {

				if(this.$route.name == 'AdminShowDomainProviderPayment') {

					return [
						{ text: 'DomainProviderPayments', path: '/admin/domain-provider-payment'},
						{ text: this.domain-provider-payment.name ?? 'DomainProviderPayment', path: '/admin/domain-provider-payment/' + this.domain-provider-payment.id}
					];

				} else if(this.$route.name == 'AdminEditDomainProviderPayment') {

					return [
						{ text: 'DomainProviderPayments', path: '/admin/domain-provider-payment'},
						{ text: this.domain-provider-payment.name ?? 'DomainProviderPayment' , path: '/admin/domain-provider-payment/' + this.domain-provider-payment.id},
						{ text: 'Editar domain-provider-payment', path: '/admin/domain-provider-payment/' + this.domain-provider-payment.id + '/edit'}	
					];

				}

			}

		},

		methods: {

			async fetchData() {

				await this.fetchDomainProviderPayment()

				this.dataLoaded = true;
				
				this.title = this.domainProviderPayment.name;

				document.title = this.title;

			},

			async fetchDomainProviderPayment() {

				let res = await showModel(this.domainProviderPaymentId);

				this.domainProviderPayment = res;

            },

		}

	}

</script>