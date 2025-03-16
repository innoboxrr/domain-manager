<template>

	<div v-if="dataLoaded">

		<breadcrumb-component :items="items" />
	    
	    <div class="uk-container uk-container-expand">

	    	<div class="uk-grid-small" uk-grid>
	    		
	    		<div class="uk-width-1-3@m uk-width-1-1@s">

					<model-card 
						:domain-provider="domainProvider" />

	    		</div>

	    		<div class="uk-width-expand uk-width-1-2@m uk-width-1-1@s">

	    			<div v-if="this.isShowView">

	    				<model-profile 
	    					:domain-provider="domainProvider" />
	    				
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

	import { showModel } from '@domainModels/domain-provider'
	import ModelCard from '@domainModels/domain-provider/widgets/ModelCard.vue'
	import ModelProfile from '@domainModels/domain-provider/widgets/ModelProfile.vue'

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

				domainProviderId: this.$route.params.id,

				domainProvider: {},

			}
		
		},

		computed: {

			isShowView() {

				return (this.$route.name == 'AdminShowDomainProvider');

			},

			items() {

				if(this.$route.name == 'AdminShowDomainProvider') {

					return [
						{ text: 'DomainProviders', path: '/admin/domain-provider'},
						{ text: this.domain-provider.name ?? 'DomainProvider', path: '/admin/domain-provider/' + this.domain-provider.id}
					];

				} else if(this.$route.name == 'AdminEditDomainProvider') {

					return [
						{ text: 'DomainProviders', path: '/admin/domain-provider'},
						{ text: this.domain-provider.name ?? 'DomainProvider' , path: '/admin/domain-provider/' + this.domain-provider.id},
						{ text: 'Editar domain-provider', path: '/admin/domain-provider/' + this.domain-provider.id + '/edit'}	
					];

				}

			}

		},

		methods: {

			async fetchData() {

				await this.fetchDomainProvider()

				this.dataLoaded = true;
				
				this.title = this.domainProvider.name;

				document.title = this.title;

			},

			async fetchDomainProvider() {

				let res = await showModel(this.domainProviderId);

				this.domainProvider = res;

            },

		}

	}

</script>