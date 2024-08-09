export class Acquired {

	constructor (publicKey, logLevel = 'error') {
		let _self = this
		this.publicKey = publicKey
		this.formOrigin = ''
		this.formUrl = ''
		this.fieldBlurCallback = null
		this.sessionId = ''
		this.componentsData = {}
		this.response = null
		this.componentName = ''

		const logLevelType = ['fatal', 'eror', 'warn', 'info', 'debug', 'trace']
		if (logLevelType.indexOf(logLevel) === -1) {
			logLevel = 'error'
		}
		this.logLevel = logLevel

		window.onmessage = function (e) {
			_self._handlePostMessage(e)
		}
	}

	components (options) {
		try {
			let _self = this
			if (!options.session) {
				throw new Error('invalid sessionId')
			}
			this.formOrigin = this._setEnviroment(options.environment)
			this.formUrl = this.formOrigin + '/v1/payment-sessions/'
			this.sessionId = options.session

			let endpoint = this.formUrl + options.session + '/' + this.publicKey
			let iframe = {}
			let isForm = false
			return {
				sessionId: options.session, isForm: isForm, iframe: iframe, create: function (el, elOptions = {}) {
					let _this = this
					let fieldElement = ''
					if (el == 'cardForm') _this.isForm = true
					if (el != 'cardForm') fieldElement = '/' + el
					_self.componentName = el
					_self.componentsData[el] = { options: elOptions }
					return {
						mount: function (id) {
							let component = document.querySelector(id)
							iframe = document.createElement('iframe')
							iframe.src = endpoint + fieldElement
							iframe.width = '100%'
							iframe.height = '100%'
							iframe.id = 'acquired-iframe-' + el
							iframe.scrolling = 'none'
							iframe.style.border = 'none'
							iframe.className = 'acquired-iframe'
							iframe.allow = 'payment ' + _self.formOrigin
							component.appendChild(iframe)

							_self.componentsData[el].iframe = iframe
							_this.iframe = iframe
						},
					}
				}, on: function (event, func) {
					if (this.isForm) throw new Error('Invalid component')
					switch (event) {
						case 'blur':
							_self.fieldBlurCallback = func
							break
						default:
							break
					}
				}
			}
		} catch (err) {
			this._log(err.message, 'error')
		}
	}

	confirmPayment (options) {
		return new Promise(async (resolve, reject) => {
			try {
				let _self = this
				_self.response = null
				let components = options.components
				let iframeDoc = components.iframe.contentWindow
				if (iframeDoc) {
					let params = { component: _self.componentName }
					if (options.hasOwnProperty('confirmParams')) {
						params = {
							confirmParams: JSON.stringify(options.confirmParams),
							component: _self.componentName
						}
					}
					this._iframePostMessage(iframeDoc, 'submit', params)
				} else {
					reject()
				}

				let timer = setInterval(function () {
					if (_self.response !== null) {
						resolve(_self.response)
						clearInterval(timer)
					}
				}, 1000)
			} catch (err) {
				this._log(err.message, 'error')
			}
		})
	}

	_handlePostMessage (e) {
		try {
			if (e.data.sessionId != this.sessionId || e.origin != this.formOrigin) {
				throw new Error('invalid post message')
			}

			switch (e.data.event) {
				case 'load-response':
					this._handleLoadResponse(e)
					break
				case 'resize-form':
					this._handleResizeForm(e)
					break
				case 'field-blur':
					this._handleFieldBlur(e)
					break
				case 'submit-response':
					this._handleSubmitResponse(e)
					break
				case 'change-form':
					this._handleChangeForm(e)
					break
				default:
					throw new Error('invalid post message event')
					break
			}
		} catch (err) {
			this._log(err.message, 'error')
		}
	}

	_handleLoadResponse (e) {
		if (e.data.response.component) {
			let component = e.data.response.component
			let iframeDoc = this.componentsData[component].iframe.contentWindow
			let options = this.componentsData[component].options

			if (e.data.response.documentHeight != undefined) {
				document.querySelector('.acquired-iframe').style.height = e.data.response.documentHeight + 'px'
			}

			if (iframeDoc) {
				let params = { component: component, logLevel: this.logLevel }
				if (Object.keys(options).length > 0) {
					params.options = JSON.stringify(options)
				}
				this._iframePostMessage(iframeDoc, 'element-mount', params)
			}
		}
	}

	_handleResizeForm (e) {
		if (e.data.response.component) {
			let component = e.data.response.component
			let iframeDoc = this.componentsData[component].iframe.contentWindow
			let options = this.componentsData[component].options

			if (e.data.response.documentHeight != undefined) {
				document.querySelector('.acquired-iframe').style.height = e.data.response.documentHeight + 'px'
			}
		}
	}

	_handleFieldBlur (e) {
		if (typeof this.fieldBlurCallback == 'function') {
			this.fieldBlurCallback(e.data.response)
		}
	}

	showComponentErrorMessage (element, response) {
		try {
			if (response.status == 'success') {
				let errorElement = document.querySelectorAll(element)
				errorElement.forEach((element, index) => {
					element.textContent = ''
				})
				return true
			}
			if (!this.componentsData[response.component]) {
				return false
			}
			let iframe = this.componentsData[response.component].iframe
			let nextElement = iframe.parentNode.nextElementSibling
			if (nextElement.className == element.substring(1)) {
				nextElement.textContent = response.message
				let iframeDoc = iframe.contentWindow
				this._iframePostMessage(iframeDoc, 'component-error', { component: response.component })
			}
		} catch (err) {
			this._log(err.message, 'error')
		}

	}

	_handleSubmitResponse (e) {
		this.response = {
			data: e.data.response, isTdsPending: function () {
				return this.data.status == 'tds_pending'
			}, isSuccess: function () {
				return this.data.status == 'success'
			}, isError: function () {
				return !(this.isTdsPending() || this.isSuccess())
			}, showMessage: function (showField) {
				const field = document.querySelector(showField)
			}
		}
	}

	_handleChangeForm (e) {
		if (e.data.response.component && e.data.response.toComponent) {
			let toComponent = e.data.response.toComponent
			if (this.componentsData[toComponent] == undefined) {
				this.componentsData[toComponent] = this.componentsData[e.data.response.component]
			}
			this.componentName = toComponent
		}
	}

	_setEnviroment (env) {
		switch (env) {
			case 'test':
				return 'https://test-pay.acquired.com'
				break
			case 'production':
				return 'https://pay.acquired.com'
				break
			default:
				throw new Error('invalid environment')
				break
		}
	}

	_log (message, type = 'error') {
		const logTypes = ['fatal', 'error', 'warn', 'info', 'debug', 'trace']

		const typeIndex = logTypes.indexOf(type)
		const levelIndex = logTypes.indexOf(this.logLevel)
		if (typeIndex <= levelIndex) {
			console.log(this._getCurrentDate() + ' [' + type.toUpperCase() + '] ' + message)
		}
	}

	_getCurrentDate () {
		const dateObj = new Date()
		return dateObj.toLocaleString()
	}

	_iframePostMessage (iframe, event, reqeust) {
		iframe.postMessage({
			event: event,
			publicKey: this.publicKey,
			sessionId: this.sessionId,
			request: reqeust
		}, this.formOrigin)
	}
}