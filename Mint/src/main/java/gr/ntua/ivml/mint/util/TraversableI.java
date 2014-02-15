package gr.ntua.ivml.mint.util;

import java.util.List;

public interface TraversableI {
	public List<? extends TraversableI> getChildren();
}
