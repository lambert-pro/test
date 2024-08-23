import { Component, OnInit } from '@angular/core';
import { ChangeDetectorRef } from '@angular/core';

declare var Acquired: any;

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  standalone: true
})
export class AppComponent implements OnInit {

  formContent: string = '';
  formType: string = '';
  sessionId: string = '01917cf6-fbbd-73b9-a2f1-da78c0046d64';
  publicKey: string = 'pk_346c8a4e32dc57bc7bb7db132bd0cbfa';

  confirmParams = {
    customer: {
      reference: 'cfe19f01-6fe5-4c82-be05-0f66054efde1',
      billing: {
        address: {
          line_1: 'Flat 1',
          line_2: '152 Aldgate Drive',
          city: 'London',
          postcode: 'E1 7RT',
          country_code: 'GB',
        },
        email: 'ejohnson@acquired.com',
        phone: {
          country_code: '44',
          number: '2039826580',
        },
      },
      shipping: {
        address_match: true,
      },
    },
    webhook_url: 'https://www.yourdomain.com/webhook',
  };

  style = {
    base: {
      backgroundColor: '#fff',
      padding: '12px 16px',
      fontFamily: 'Tahoma',
      fontSize: '15px',
      fontWeight: '400',
      border: '1px solid #ccc',
      borderColor: 'gray',
      borderRadius: '6px',
    },
    focus: {
      color: 'green',
      borderColor: 'green',
    },
    invalid: {
      color: 'orange',
      borderColor: 'orange',
    },
    placeholder: {
      color: 'gray',
    },
  };

  constructor(private cdr: ChangeDetectorRef) {}

  ngOnInit(): void {
    this.loadAcquiredSDK().then(() => {
      this.initAcquired();
    }).catch(err => {
      console.error('Failed to load Acquired SDK', err);
    });
  }

  loadAcquiredSDK(): Promise<void> {
    return new Promise((resolve, reject) => {
      const script = document.createElement('script');
      script.src = 'https://cdn.acquired.com/sdk/v1.1/acquired.js';
      script.async = true;
      script.onload = () => {
        resolve();
      };
      script.onerror = (err) => {
        reject(err);
      };
      document.body.appendChild(script);
    });
  }

  initAcquired(): void {
    if (this.formContent !== '') {
      console.log(234)

      const acquired = new Acquired(this.publicKey, 'debug');
      const options = { session: this.sessionId, environment: 'test' };
      const components = acquired.components(options);

      console.log(this.formType)
      setTimeout(() => {
        if (this.formType === 'card') {
          console.log(document)
          const paymentComponent = components.create('cardForm', { style: this.style });
          if (document.querySelector('#payment-component')) {
            paymentComponent.mount('#payment-component');
          }
        } else if (this.formType === 'payment') {
          const paymentComponent = components.create('payment', { style: this.style });
          if (document.querySelector('#payment-component')) {
            paymentComponent.mount('#payment-component');
          }
        } else if (this.formType === 'individual') {
          if (document.querySelector('#card-number-component')) {
            const cardNumberComponent = components.create('cardNumber', { style: this.style });
            cardNumberComponent.mount('#card-number-component');
          }
          if (document.querySelector('#card-holder-component')) {
            const cardholderNameComponent = components.create('cardholderName', { style: this.style });
            cardholderNameComponent.mount('#card-holder-component');
          }
          if (document.querySelector('#card-expire-date-component')) {
            const cardExpireDateComponent = components.create('cardExpireDate', { style: this.style });
            cardExpireDateComponent.mount('#card-expire-date-component');
          }
          if (document.querySelector('#card-cvv-component')) {
            const cardCvvComponent = components.create('cardCvv', { style: this.style });
            cardCvvComponent.mount('#card-cvv-component');
          }
        }
      }, 0);

      const form = document.querySelector('form');
      if (form) {
        form.onsubmit = null; // 清除之前的事件监听器
        form.addEventListener('submit', async (event) => {
          event.preventDefault();
          try {
            const response = await acquired.confirmPayment({ components, confirmParams: this.confirmParams });
            (document.getElementById('response') as HTMLTextAreaElement).value = JSON.stringify(response, null, 2);

            if (response.isTdsPending()) {
              window.location.href = response.data.redirect_url;
            } else if (response.isSuccess()) {
              (document.getElementById('response') as HTMLTextAreaElement).value = JSON.stringify(response, null, 2);
            } else if (response.isError()) {
              acquired.showComponentErrorMessage('.component-error', response.data);
              (document.querySelector('#response') as HTMLTextAreaElement).textContent = JSON.stringify(response.data);
            }
          } catch (error: any) {
            (document.getElementById('response') as HTMLTextAreaElement).value = `Error: ${error.message}`;
            console.error('Payment error:', error);
          }
        });
      }
    }
  }

  handleButtonClick(type: string): void {
    this.formType = type;
    this.formContent = ''; // 先清空内容
    setTimeout(() => {
      this.formContent = this.getFormHtml(type);
      this.cdr.detectChanges(); // 手动触发变更检测，确保DOM更新
      setTimeout(() => {
        this.initAcquired(); // 确保DOM更新后再调用initAcquired
      }, 0);
    }, 0); // 设置新的内容
  }


  getFormHtml(type: string): string {
    let formHtml = '';

    if (type === 'card' || type === 'payment') {
      formHtml = `
      <h2 id="title">${type} 支付表单</h2>
      <form id="${type}-form" class="form-main">
        <div id="payment-component"></div>
        <button type="submit" id="submit">Submit</button>
      </form>`;
    } else if (type === 'individual') {
      formHtml = `
      <h2 id="title">${type} 支付表单</h2>
      <form id="individual-form" class="form-main">
        <div id="individual-payment-component">
          <div class="input-field" id="card-holder-component"></div>
          <p class="component-error"></p>
          <div class="input-field" id="card-number-component"></div>
          <p class="component-error"></p>
          <div class="input-field" id="card-expire-date-component"></div>
          <p class="component-error"></p>
          <div class="input-field" id="card-cvv-component"></div>
          <p class="component-error"></p>
          <button type="submit" id="submit">Submit</button>
        </div>
      </form>`;
    }
    console.log(345)
    console.log(formHtml)
    return formHtml;
  }
}
