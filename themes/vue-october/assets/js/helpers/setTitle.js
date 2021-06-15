export default function setTitle(data) {
  const title = document.title.split(" - ");
  title[1] =  data.title;
  document.title = title.join(" - ");
}