import './App.css';
import { useEffect, useState } from 'react';
import { Acquired } from './assets/js/Acquired.js'

function App() {
  const sessionId = '019131f6-29b5-7067-bfd9-c2b87a46f239';
  const publicKey = 'pk_346c8a4e32dc57bc7bb7db132bd0cbfa';
  const confirmParams = {
    customer: {
      reference: 'cfe19f01-6fe5-4c82-be05-0f66054efde1',
      billing: {
        address: {
          line_1: 'Flat 1',
          line_2: '152 Aldgate Drive',
          city: 'London',
          postcode: 'E1 7RT',
          country_code: 'GB'
        },
        email: 'ejohnson@acquired.com',
        phone: {
          country_code: '44',
          number: '2039826580'
        }
      },
      shipping: {
        address_match: true
      }
    },
    webhook_url: 'https://www.yourdomain.com/webhook'
  };
  const style = {
    base: {
      backgroundColor: '#fff',
      padding: '12px 16px',
      fontFamily: 'Tahoma',
      fontSize: '15px',
      fontWeight: '400',
      border: '1px solid #ccc',
      borderColor: 'gray',
      borderRadius: '6px'
    },
    focus: {
      color: 'green',
      borderColor: 'green'
    },
    invalid: {
      color: 'orange',
      borderColor: 'orange'
    },
    placeholder: {
      color: 'gray'
    }
  };

  const [formContent, setFormContent] = useState('');
  const [formType, setFormType] = useState('');

  useEffect(() => {
    if (formContent !== '') {
      const acquired = new Acquired(publicKey, 'debug');
      const options = { session: sessionId, environment: 'test' };
      const components = acquired.components(options);

      if (formType === 'card') {
        const paymentComponent = components.create('cardForm', { style });
        paymentComponent.mount('#payment-component');
      } else if (formType === 'payment') {
        const paymentComponent = components.create('payment', { style });
        paymentComponent.mount('#payment-component');
      } else if (formType === 'individual') {
        const cardNumberComponent = components.create('cardNumber', {
          style: style
        });
        cardNumberComponent.mount('#card-number-component');

        const cardholderNameComponent = components.create('cardholderName', {
          style: style
        });
        cardholderNameComponent.mount('#card-holder-component');

        const cardExpireDateComponent = components.create('cardExpireDate', {
          style: style
        });
        cardExpireDateComponent.mount('#card-expire-date-component');

        const cardCvvComponent = components.create('cardCvv', {
          style: style
        });
        cardCvvComponent.mount('#card-cvv-component');;
      }

      const form = document.querySelector('form');
      form.addEventListener('submit', async (event) => {
        event.preventDefault();
        try {
          const response = await acquired.confirmPayment({ components, confirmParams });
          document.getElementById('response').value = JSON.stringify(response, null, 2);
          console.log('Payment response:', response);

          if(response.isTdsPending() ){
            console.log(1111,response.data);
            window.location.href = response.data.redirect_url;
          }
          if(response.isSuccess()){
            const messageContainer = document.querySelector('#error-message');
            messageContainer.textContent = JSON.stringify(response.data);
          }
          if(response.isError()) {
            acquired.showComponentErrorMessage('.component-error', response.data);
            const messageContainer = document.querySelector('#error-message');
            messageContainer.textContent = JSON.stringify(response.data);
          }

        } catch (error) {
          document.getElementById('response').value = `Error: ${error.message}`;
          console.error('Payment error:', error);
        }
      });

    }
  }, [formContent, formType]);

  const handleButtonClick = (type) => {
    setFormType(type);
    let formHtml = getFormHtml(type);
    setFormContent(''); // 先清空内容
    setTimeout(() => setFormContent(formHtml), 0); // 设置新的内容
  };

  function getFormHtml(type){
    let formHtml;
    if (type === 'card') {
      formHtml = `<h2 id="title">${type} 支付表单</h2>
      <form id="${type}-form" className="form-main">
        <div id="payment-component"></div>
        <button type="submit" id="submit">Submit</button>
      </form>`;
    } else if (type === 'payment') {
      formHtml = `<h2 id="title">${type} 支付表单</h2>
      <form id="${type}-form" className="form-main">
        <div id="payment-component"></div>
        <button type="submit" id="submit">Submit</button>
      </form>`;
    } else if (type === 'individual') {
      formHtml = `<h2 id="title">${type} 支付表单</h2>
      <form id="${type}-form" className="form-main">
        <div id="payment-component">
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
    return formHtml;
  }

  return (<div className="App">
      <header className="App-header">
        <p>Edit <code>src/App.js</code> and save to reload.</p>
        <a className="App-link" href="https://zh-hans.react.dev/" target="_blank" rel="noopener noreferrer">
          Learn React
        </a><br/>

        <button className="button" onClick={() => handleButtonClick('card')}>card-form 支付</button>
        <button className="button" onClick={() => handleButtonClick('payment')}>payment-form 支付</button>
        <button className="button" onClick={() => handleButtonClick('individual')}>individual-form 支付</button>
      </header>
      <div className="Div-form" dangerouslySetInnerHTML={{ __html: formContent }}></div>
      <div>
        <textarea id="response" readOnly></textarea>
      </div>
    </div>);
}

export default App;
