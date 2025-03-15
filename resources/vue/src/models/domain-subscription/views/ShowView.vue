<template>

	<div v-if="dataLoaded">

		<breadcrumb-component :items="items" />
	    
	    <div class="uk-container uk-container-expand">

	    	<div class="uk-grid-small" uk-grid>
	    		
	    		<div class="uk-width-1-3@m uk-width-1-1@s">

					<model-card 
						:domain-subscription="domainSubscription" />

	    		</div>

	    		<div class="uk-width-expand uk-width-1-2@m uk-width-1-1@s">

	    			<div v-if="this.isShowView">

	    				<model-profile 
	    					:domain-subscription="domainSubscription" />
	    				
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

	import { showModel } from '@models/domain-subscription'
	import ModelCard from '@models/domain-subscription/widgets/ModelCard.vue'
	import ModelProfile from '@models/domain-subscription/widgets/ModelProfile.vue'

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

				domainSubscriptionId: this.$route.params.id,

				domainSubscription: {},

			}
		
		},

		computed: {

			isShowView() {

				return (this.$route.name == 'AdminShowDomainSubscription');

			},

			items() {

				if(this.$route.name == 'AdminShowDomainSubscription') {

					return [
						{ text: 'DomainSubscriptions', path: '/admin/domain-subscription'},
						{ text: this.domain-subscription.name ?? 'DomainSubscription', path: '/admin/domain-subscription/' + this.domain-subscription.id}
					];

				} else if(this.$route.name == 'AdminEditDomainSubscription') {

					return [
						{ text: 'DomainSubscriptions', path: '/admin/domain-subscription'},
						{ text: this.domain-subscription.name ?? 'DomainSubscription' , path: '/admin/domain-subscription/' + this.domain-subscription.id},
						{ text: 'Editar domain-subscription', path: '/admin/domain-subscription/' + this.domain-subscription.id + '/edit'}	
					];

				}

			}

		},

		methods: {

			async fetchData() {

				await this.fetchDomainSubscription()

				this.dataLoaded = true;
				
				this.title = this.domainSubscription.name;

				document.title = this.title;

			},

			async fetchDomainSubscription() {

				let res = await showModel(this.domainSubscriptionId);

				this.domainSubscription = res;

            },

		}

	}

</script>