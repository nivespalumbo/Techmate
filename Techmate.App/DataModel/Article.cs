using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Techmate.DataModel
{
    public class Article
    {
        public Article(int number, string author, string link, string section, string title, string subtitle, string text)
        {
            Number = number;
            Author = author;
            Link = link;
            Section = section;
            Title = title;
            Subtitle = subtitle;
            Text = text;
            Images = new List<string>();
            Attachments = new List<string>();
        }

        public int Number { get; private set; }
        public string Author { get; private set; }
        public string Link { get; private set; }
        public string Section { get; private set; }
        public string Title { get; private set; }
        public string Subtitle { get; private set; }
        public string Text { get; private set; }
        public IEnumerable<string> Images { get; private set; }
        public IEnumerable<string> Attachments { get; private set; }
    }
}
