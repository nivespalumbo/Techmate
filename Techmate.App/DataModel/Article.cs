using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Techmate.DataModel
{
    public class Article
    {
        public Article(string number, string author, string link, string section, string mag, string title, string subtitle, string text, string[] images, string[] attach)
        {
            Number = number;
            Author = author;
            Link = link;
            Section = section;
            Magazine = mag;
            Title = title;
            Subtitle = subtitle;
            Text = text;
            Images = images;
            Attachments = attach;
        }

        public string Number { get; private set; }
        public string Author { get; private set; }
        public string Link { get; private set; }
        public string Section { get; private set; }
        public string Magazine { get; private set; }
        public string Title { get; private set; }
        public string Subtitle { get; private set; }
        public string Text { get; private set; }
        public string[] Images { get; private set; }
        public string[] Attachments { get; private set; }
    }
}
