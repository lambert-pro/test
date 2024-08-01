const lists = [
  {title:'China',isGreat:true,id:1},
  {title:'Japan',isGreat:false,id:2},
  {title:'United Kingdom',isGreat:false,id:3},
]

function ShowList(){
  const listItem = lists.map(list =>
    <li
      key={list.id}
      style={{color: list.isGreat ? 'red' : 'black'}}
    >
      {list.title}
    </li>
  );
  return (
    <ul>{listItem}</ul>
  )
}

export default ShowList;