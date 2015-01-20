using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Techmate.Common;

namespace Techmate.DataModel
{
    public class Magazine
    {
        public Magazine(int number, string cover, string color, string theabstract, string publishDate, string content)
        {
            Number = number;
            Cover = cover;
            Color = color;
            PublishDate = publishDate;
            Abstract = theabstract;
            Content = content;
            Articles = new ObservableCollection<Article>();
        }

        public int Number { get; private set; }
        public string Cover { get; private set; }
        public string Color { get; private set; }
        public string PublishDate { get; private set; }
        public string Abstract { get; private set; }
        public string Content { get; private set; }
        public ObservableCollection<Article> Articles { get; private set; }
    }
}
