import React, { useEffect } from 'react';

function PaymentForm() {
  useEffect(() => {
    if (window.acquired) {
      window.acquired.init({
        // 配置你的初始化选项
        session_id: '0190fc16-7175-71e1-bbf7-f6103df6d8f1',
        // 其他配置项
      });

      window.acquired.renderForm({
        container: '#payment-form', // 你希望表单渲染的元素ID
        onSuccess: function (response) {
          console.log('Payment successful', response);
        },
        onError: function (error) {
          console.log('Payment error', error);
        },
      });
    } else {
      console.error('Acquired.js未加载');
    }
  }, []);

  return (
    <div id="payment-form">
      <h2>支付表单</h2>
    </div>
  );
}

export default PaymentForm;
