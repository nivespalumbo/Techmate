using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Techmate.DataModel
{
    public class Techbreak
    {
        private const string YoutubeWatchBaseUrl = "http://www.youtube.com/watch?v=";
        private const string YoutubeEmbedHtmlFragment = @"http://www.youtube.com/embed/{0}?rel=0&fs=0";

        public string Title { get; set; }
        public string Summary { get; set; }
        public string VideoUrl { get; set; }
        public string ImageUrl { get; set; }
        public DateTime Published { get; set; }
        public string VideoId { get; set; }

        public string ExternalUrl
        {
            get
            {
                return YoutubeWatchBaseUrl + VideoId;
            }
        }

        public string EmbedHtmlFragment
        {
            get
            {
                return string.Format(YoutubeEmbedHtmlFragment, VideoId);
            }
        }
    }
}
