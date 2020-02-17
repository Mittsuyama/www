React 官方文档学习笔记
计算机 | 笔记 | 网页 | React | 前端
React是一个为数据提供渲染为 HTML 视图的 开源 JavaScript 库。React 视图通常采用包含以自定义 HTML 标记规定的其他组件的组件渲染。React 为程序员提供了一种子组件不能直接影响外层组件（"data flows down"）的模型，数据改变时对 HTML 文档的有效更新，和现代单页应用中组件之间干净的分离。
2019-8-19

## 主要概念

官方文档链接：

[Hello World - React](https://www.reactjscn.com/docs/hello-world.html)

### JSX 简介

**JSX 会防止 XSS 攻击**，自动过滤传进来的值。

如果想要渲染 HTML 代码，需要用 *dangerouslySetInnerHTML*

```jsx
<div dangerouslySetInnerHTML={ HTML_CODE } />
```

**JSX 代表 Object**，Babel 转换器会调用 *React.createElement()* 来转换成一个对象：

```jsx
// 注意: 以下示例是简化过的（不代表在 React 源码中是这样）
const element = {
  type: 'h1',
  props: {
    className: 'greeting',
    children: 'Hello, world'
  }
};
```

### 元素渲染

即使每次更新都调用一遍 *Reder()* ，渲染的时候也只会渲染改变了的部分。

希望开发者将页面视为**一个个特定的固定的内容**（就像帧动画的每一帧），而不是一直处于变化之中。

### 组件 & Props

可以用函数 / 类定义组件

函数：

```jsx
function Welcome(props) {
  return <h1>Hello, {props.name}</h1>;
}
```



用 ES6 类来定一个组件：

```jsx
class Welcome extends React.Component {
  render() {
    return <h1>Hello, {this.props.name}</h1>;
  }
}
```



⚠️ 注意：组件名必须是大写，使用 *<Welcome />* 表示一个组件，而 *<welcome />* 会被认为是一个原生 *HTML* 标签

### State & 生命周期

使用构造函数定义 state：

```react
constructor(props) {
  super(props);
  this.state = { data: DATA };
}
```



⚠️ 关于 state 更新：

```jsx
this.state.comment = 'Hello';
```

不推荐使用上述代码来更改 state 值，此操作不会触发页面的更新，而应该使用 *setState()* 。

setState 的同步异步问题：

React 可以将多个 *setState()* 调用并**合并**俩提高性能。

因为 *this.props* 和 *this.state* **可能是异步更新**的（注意是「可能」是异步更新），你不应该依靠它们的值，来计算下一个状态。

而应该用函数的形式：

```jsx
// Correct
this.setState((prevState, props) => ({
  counter: prevState.counter + props.increment
}));
// 而不是 { counter: this.state.counter + 1 }
```



什么时候是异步的？

1. React 的时间函数
2. React 的生命周期函数

同步：其他的异步函数，比如：定时器，promise 等（*setState()* 之后直接 *render()*）

#### 关于合并

React 会合并相邻的 *setState({})* ，即：

```jsx
// 初始 counter 为 0

// 此时的 this.state.counter = 0 
this.setState({ counter: this.state.counter + 1 }); 

// 此时的 this.state.counter 同样为 0 
this.setState({ counter: this.state.counter + 1 });
```



但是 React 不会合并 *setState()* 接受函数的情况，接受函数时，React 会保证每次传入的值都是最新的，但是页面刷新会合并（即更新多次数据，更新一次页面）

### 事件处理

React 事件绑定属性的命名采用**驼峰式写法**，而不是小写，比如 *onClick* 。

React 中不能使用 *return false;* 来阻止默认行为，必需**显示地**使用 *return false;*。

```jsx
function handleClick(e) {
  e.preventDefault();
  console.log('The link was clicked.');
}
```



查看 *SyntheticEvent* 参考指南：

[SyntheticEvent - React](https://www.reactjscn.com/docs/events.html)

拥有和浏览器原事件一样的接口，包括 *stopProgagation()* 和 *preventDefault()* 。

**事件池**：*SyntheticEvent* 是共享的。那就意味着在调用事件回调之后，*SyntheticEvent* 对象将会被重用，并且所有属性会被置空。这是出于性能因素考虑的。 因此，您无法以异步方式访问事件。即：

```jsx
function onClick(event) {
  console.log(event); // => nullified object.
  console.log(event.type); // => "click"
  const eventType = event.type; // => "click"

  setTimeout(function() {
    console.log(event.type); // => null
    console.log(eventType); // => "click"
  }, 0);

  // Won't work. this.state.clickEvent will only contain null values.
  this.setState({clickEvent: event});

  // You can still export event properties.
  this.setState({eventType: event.type});
}
```



### 条件渲染

运算符 *&&* 来实现条件渲染

```jsx
{unreadMessages.length > 0 &&
  <h2>
    You have {unreadMessages.length} unread messages.
  </h2>
}
```



同理还可以用**问号表达式**来进行渲染。

### 列表 & keys

用 *map()* 进行循环渲染，key 可以在 DOM 中的某些元素被增加或删除的时候帮助 React 识别哪些元素发生了变化。

### 表单

需要为每一个表单元素添加 *onChange* 属性，并传入表单改变时，对新的值的处理：

```react
handleChange(event) {
	// 表单元素 <input type = "text" value = {this.state.value} />
  this.setState({ value: event.target.value });
}
```



### 状态提升

使用 react 经常会遇到几个组件需要共用状态数据的情况。这种情况下，我们最好将这部分共享的状态**提升至他们最近的父组件**当中进行管理。