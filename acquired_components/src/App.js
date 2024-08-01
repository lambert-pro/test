import logo from './logo.svg';
import './App.css';
import MyButton from './components/MyButton.js'
import './components/MyButton.css'
import List from './components/List'
import MyClick from './components/MyClick'

function App() {
  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
        <p>
          Edit <code>src/App.js</code> and save to reload.
        </p>
        <a
          className="App-link"
          href="https://zh-hans.react.dev/"
          target="_blank"
          rel="noopener noreferrer"
        >
          Learn React
        </a>
        <MyButton />
        <MyButton />
        <List />
        <MyClick />
      </header>
    </div>
  );
}

export default App;
