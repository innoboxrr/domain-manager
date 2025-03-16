<template>

	<div v-if="dataLoaded">

		<breadcrumb-component :items="items" />
	    
	    <div class="uk-container uk-container-expand">

	    	<div class="uk-grid-small" uk-grid>
	    		
	    		<div class="uk-width-1-3@m uk-width-1-1@s">

					<model-card 
						:domain="domain" />

	    		</div>

	    		<div class="uk-width-expand uk-width-1-2@m uk-width-1-1@s">

	    			<div v-if="this.isShowView">

	    				<model-profile 
	    					:domain="domain" />
	    				
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

	import { showModel } from '@domainModels/domain'
	import ModelCard from '@domainModels/domain/widgets/ModelCard.vue'
	import ModelProfile from '@domainModels/domain/widgets/ModelProfile.vue'

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

				domainId: this.$route.params.id,

				domain: {},

			}
		
		},

		computed: {

			isShowView() {

				return (this.$route.name == 'AdminShowDomain');

			},

			items() {

				if(this.$route.name == 'AdminShowDomain') {

					return [
						{ text: 'Domains', path: '/admin/domain'},
						{ text: this.domain.name ?? 'Domain', path: '/admin/domain/' + this.domain.id}
					];

				} else if(this.$route.name == 'AdminEditDomain') {

					return [
						{ text: 'Domains', path: '/admin/domain'},
						{ text: this.domain.name ?? 'Domain' , path: '/admin/domain/' + this.domain.id},
						{ text: 'Editar domain', path: '/admin/domain/' + this.domain.id + '/edit'}	
					];

				}

			}

		},

		methods: {

			async fetchData() {

				await this.fetchDomain()

				this.dataLoaded = true;
				
				this.title = this.domain.name;

				document.title = this.title;

			},

			async fetchDomain() {

				let res = await showModel(this.domainId);

				this.domain = res;

            },

		}

	}

</script>